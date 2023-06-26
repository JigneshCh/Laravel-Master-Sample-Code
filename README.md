## Laravel Master CODE | Common Model, Controller and View  | Dynamic data with relational data
This is a sample Laravel code with Crud function and it use common code for multiple modals 

 - Layout and templating
 - Form with input components
 - Datatables for item listing
 - Reusable code for different module
 - Validation rules for Model and common function for form validation 
 - Automatic Relational Model data, based on defined rules
 - File upload in any form will automatically upload in specific Model path with reference table relation 
 - Image crop and thumb image functions with Model getter and setter functions
 - Model with boot events create, update
 - Model with relationship hasOne or hasMany
 - Auto generate slug for Model if Model have slug field from boot events
 

## Master Controller (Controller.php)

 - Use middleware function to check access in constructor
 - Variable `context` to refer current model
 - Return data in json format if request type ajax 
 - Store model reference file with Refefile relationship  -- `hasOne` or `hasMany`
 - Set Params function to store model relational data -- `serialize` or `unserialize`
 - Softdelete and restore functions
 - Store specific model data with validation
 - Datatables functions for item listing -- `yajra datatable`

## Model controller (ArticlesController.php)

 - Only need to define `context` Variable to define model name
 - Define validation rules is optional

## Master Model (BaseModel.php)

 - Boot events function for data insert or update
 - Auto generate and set slug value for model
 - Define relationship with model `Refefile` to manage uploaded file with any module

## Master List, Create, Edit, View (./system/)

 - Index: common code for listing all module with datatable js and sweet alert js
 - Create common form for all module with validation errors
 
## Model Create, Edit, View (./articals/)

 - Index, Create and edit file will be same for all module as its extend master view file
 - Form with defined components for inputs
 - User param variable in form field to define relational model 