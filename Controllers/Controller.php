<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use Illuminate\Support\Facades\Auth;



class Controller extends BaseController
{
    protected $user;
    protected $context = null;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if (!$this->context && @$request->context) {
                $this->context = $request->context;
                $this->modal = 'App\\' . ucfirst($this->context);
            }
            $access  = canAccess($this->context, 'Access');


            if ($access !== true) {
                Session::flash('flash_error', trans('common.responce_msg.access_denied'));
                return redirect()->back();
            }
            return $next($request);
        });
    }

    public function canAccess($action)
    {
        $access  = canAccess($this->context, $action);

        if ($access !== true) {
            Session::flash('flash_error', trans('common.responce_msg.access_denied'));
            return redirect()->back();
        }
    }

    public function returnView($file, $data = [])
    {
        return view('admin.' . $this->context . '.' . $file, $data);
    }

    public function index(Request $request)
    {

        $this->canAccess('View');
        // return view('admin.'.$this->context.'.index');
        return $this->returnView('index');
    }

    public function create(Request $request)
    {
        $this->canAccess('Add');
        // return view('admin.'.$this->context.'.create');
        return $this->returnView('create');
    }

    public function show($id, Request $request)
    {
        $this->canAccess('View');
        $item = $this->modal::where("id", $id)->first();
        if (!$item) {
            Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
            return redirect()->route('admin.' . $this->context);
        }
        return view('admin.' . $this->context . '.show', compact('item'));
    }

    public function edit($id, Request $request)
    {
        $this->canAccess('Edit');
        $result = array();
        $item = $this->modal::findOrFail($id);
        $this->setParams($item);
        if ($item) {
            $result['data'] = $item;
            $result['code'] = 200;
        } else {
            $result['message'] = trans('common.responce_msg.something_went_wr');
            $result['code'] = 400;
            Session::flash('flash_error', trans('common.responce_msg.data_not_found'));
            return redirect()->route('admin.' . $this->context);
        }
        if ($request->ajax()) {
            return response()->json($result, $result['code']);
        } else {
            return $this->returnView('edit', compact('item'));
        }
    }

    public function datatable(Request $request)
    {

        $record = $this->modal::where("id", ">", 0);
        if ($request->has('status') && $request->get('status') != 'all' && $request->get('status') != '') {
            $record->where('status', $request->get('status'));
        }
        if ($request->has('id') && $request->get('id') != '') {
            $record->where('id', $request->get('id'));
        }
        if ($request->has('enable_deleted') && $request->enable_deleted == 1) {
            $record->onlyTrashed();
        }
        return Datatables::of($record)->make(true);
    }

    public function store(Request $request)
    {

        $result = array();
        $varr = [
            'title' => 'required',
        ];

        $form_action = $request->action;
        $input = $request->except(['']);

        $input['status'] = 0;
        if (isset($input['status'])) {
            $input['status'] = 1;
        }

        $input['admin'] = 0;
        if (isset($input['admin'])) {
            $input['admin'] = 1;
        }

        $item = $this->modal::create($input);

        if ($item) {
            $files = $request->file();
            if ($files) {
                foreach ($files as $fkey => $file) {
                    if (is_array($file)) {
                        $ufile = $request->file($fkey);
                        $this->uploadModalReferenceFile($ufile, $item, $request->input($fkey . '_type'), []);
                    } else {
                        $ufile = [$request->file($fkey)];
                        $this->uploadModalReferenceFile($ufile, $item, $request->input($fkey . '_type'), [], false);
                    }
                }
            }
            $result['message'] = trans('common.responce_msg.record_created_succes');
            $result['code'] = 200;
        } else {
            $result['message'] = trans('common.responce_msg.something_went_wr');
            $result['code'] = 400;
        }
        $this->updatePivot($request, $item);
        $this->updateParams($request, $item);
        \Cache::forget('site_' . $this->context);

        if ($request->ajax()) {
            return response()->json($result, $result['code']);
        } else {
            Session::flash('flash_message', $result['message']);
            if ($request->has('previous_url') && $request->previous_url != "") {
                return redirect($request->previous_url);
            }
            if ($form_action == 'createandclose') {
                return redirect()->route('admin.' . $this->context);
            } else if ($form_action == 'createandnew') {
                return redirect()->route('admin.' . $this->context . '.create');
            } else {
                return redirect()->route($this->context . '.edit', [str_singular($this->context) => $item->id]);
            }
        }
    }

    public function updatePivot(Request $request, $item)
    {
        $relations = $request->relation;
        if ($relations) {
            foreach ($relations as $md => $keys) {
                $relationMethod = $item->$md();
                $relationMethod->detach();

                foreach ($keys as $key) {
                    $relationMethod->attach($key);
                }
            }
        }
    }

    public function setParams(&$item)
    {
        $model = new $this->modal();
        if (\Schema::hasColumn($model->getTable(), "param")) {
            if ($item->param && $item->param != 'null') {
                $param = unserialize($item->param);
            } else {
                $param = [];
            }
            $item->param  = $param;
        }
    }
    public function updateParams(Request $request, $item)
    {
        $param = $request->param;

        $model = new $this->modal();
        if (\Schema::hasColumn($model->getTable(), "param")) {
            $item->param = serialize($param);

            $item->save();
        }
    }

    public function update($id, Request $request)
    {

        $result = array();
        $this->validation('edit', $request);
        $item = $this->modal::where("id", $id)->first();
        $requestData = $request->except(['']);
        $form_action = $request->action;

        if ($item) {
            $item->update($requestData);
            $files = $request->file();
            if ($files) {
                foreach ($files as $fkey => $file) {
                    if (is_array($file)) {
                        $ufile = $request->file($fkey);
                        $this->uploadModalReferenceFile($ufile, $item, $request->input($fkey . '_type'), []);
                    } else {
                        $ufile = [$request->file($fkey)];
                        $this->uploadModalReferenceFile($ufile, $item, $request->input($fkey . '_type'), [], false);
                    }
                }
            }
            $this->updatePivot($request, $item);
            $this->updateParams($request, $item);

            \Cache::forget('site_' . $this->context);

            $result['message'] = trans('common.responce_msg.record_updated_succes');
            $result['code'] = 200;
        } else {
            $result['message'] = trans('common.responce_msg.something_went_wr');
            $result['code'] = 400;
        }
        if ($request->ajax()) {
            return response()->json($result, $result['code']);
        } else {
            Session::flash('flash_message', $result['message']);
            if ($request->has('previous_url') && $request->previous_url != "") {
                return redirect($request->previous_url);
            }

            if ($form_action == 'createandclose') {
                return redirect()->route('admin.' . $this->context);
            } else if ($form_action == 'createandnew') {
                return redirect()->route('admin.' . $this->context . '.create');
            } else {
                return redirect()->route($this->context . '.edit', [str_singular($this->context) => $item->id]);
            }
        }
    }

    function validationrule()
    {
        return [];
    }

    public function recover(Request $request)
    {
        $id = $request->id;
        $item = $this->modal::withTrashed()->where('id', $id)->first();
        if ($item) {
            $item->restore();
            $result['message'] = trans('common.responce_msg.record_restore_succes');
            $result['code'] = 200;
        } else {
            $result['message'] = trans('common.responce_msg.something_went_wr');
            $result['code'] = 400;
        }
        \Cache::forget('site_' . $this->context);
        if ($request->ajax()) {
            return response()->json($result, $result['code']);
        } else {
            Session::flash('flash_message', $result['message']);
            return redirect()->route('admin.' . $this->context);
        }
    }
    public function destroy($id, Request $request)
    {
        $this->canAccess('Delete');
        $item = $this->modal::where("id", $id)->first();
        $is_hard_delete = 0;
        if (!$item) {
            $item = $this->modal::withTrashed()->where('id', $id)->first();
            $is_hard_delete = 1;
        }
        $roll_id = 0;
        $result = array();
        if ($item) {
            if ($is_hard_delete == 1) {
                $item->forceDelete();
            } else {
                $item->delete();
            }
            $result['message'] = trans('common.responce_msg.record_deleted_succes');
            $result['code'] = 200;
            $roll_id = $item->id;
        } else {
            $result['message'] = trans('common.responce_msg.something_went_wr');
            $result['code'] = 400;
        }

        \Cache::forget('site_' . $this->context);

        if ($request->ajax()) {
            return response()->json($result, $result['code']);
        } else {
            Session::flash('flash_message', $result['message']);
            if ($request->has('previous_url') && $request->previous_url != "") {
                return redirect($request->previous_url);
            }
            if ($roll_id) {
                return redirect()->route('admin.' . $this->context, ['id', $item->id]);
            } else {
                return redirect()->route('admin.' . $this->context);
            }
        }
    }

    public function validation($mode, $request)
    {
        $validationrules = $this->validationrule();
        $rules = [];
        $msgs = [];
        $fieldTranslation = [];
        foreach ($validationrules as $field => $validationrule) {
            $apply = @$validationrule['apply'];
            if (is_null($apply) || $apply == '' || ($apply == $mode || $apply == 'all')) {
                $rules[$field] = @$validationrule['rule'];
                $messages = @$validationrule['message'];
                if ($messages) {
                    foreach ($messages as $rule => $message) {
                        $msgs[$field . '.' . $rule] = $message;
                    }
                }
                $fieldTranslation[$field] = @$validationrule['field'];
            }
        }
        $this->validate($request, $rules, $msgs, $fieldTranslation);
    }

    public function ordering(Request $request)
    {
        $id = $request->id;
        $ordering = $request->ordering;

        $obj = $this->modal::find($id);
        $obj->ordering = $ordering;
        $obj->update();
        \Cache::forget('site_' . $this->context);

        $result['message'] = trans('common.responce_msg.record_order_succes');
        $result['code'] = 200;

        if ($request->ajax()) {
            return response()->json($result, $result['code']);
        } else {
            Session::flash('flash_message', $result['message']);
            if ($roll_id) {
                return redirect()->route('admin.' . $this->context, ['id', $item->id]);
            } else {
                return redirect()->route('admin.' . $this->context);
            }
        }
    }
    public function uploadModalReferenceFile($files, $item, $type, $imgs_priority, $multiple = true)
    {
        $path = 'uploads/' . $item->getTable() . '/' . $item->id;
        $upath = public_path('storage') . '/' . $path;
        $path_thumb = $upath . '/thumb';
        \File::exists($path_thumb) or mkdir($path_thumb, 0777, true);
       $upload = 0;

        foreach ($files as $i => $file) {

            $timestamp = uniqid();
            $real_name = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $name = $timestamp . "_" . $item->id . "." . $extension;

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'PNG', 'JPEG', 'JPG'])) {

                $img = Image::make($file->getRealPath(), array(
                    'width' => 100,
                    'height' => 100,
                    'grayscale' => false
                ));

                $img->save($path_thumb . '/' . $name);
            }
            $file->move($upath, $name);

            $requestData = array();
            $requestData['refe_file_path'] = $path;
            $requestData['refe_file_name'] = $name;
            $requestData['file_real_name'] = $real_name;
            $requestData['priority'] = (isset($imgs_priority[$real_name])) ? $imgs_priority[$real_name] : 0;
            $requestData['refe_field_id'] = $item->id;
            $requestData['refe_table_name'] = $item->getTable();
            $requestData['file_type'] = $type;
            $Refefile = \App\Refefile::create($requestData);

            if (!$multiple) {
                $refes = \App\Refefile::where('refe_file_path', $path)->where('file_type', $type)->where('id', '!=', $Refefile->id)->get();
                foreach ($refes as $refe) {
                    removeRefeImage($refe);
                }
            }
            $upload++;
        }
        return $upload;
    }
}
