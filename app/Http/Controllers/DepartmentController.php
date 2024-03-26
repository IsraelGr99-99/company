<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Mandaremos los datos de depatamentos en formato JSON
     */
    public function index()
    {
        //Variable recibe los datos de departementos 
        $departments = Department::all();        
        //Los regresa en formato JSON
        return response()->json($departments);
    }

    /**
     * Reglas para los datos a guardar en la bd
     */
    public function store(Request $request)
    {
        //Para el campo name vamos a pedir que sea requerido de tipo string con un min de 1 caracter y max de 100 caracteres
        $rules = ['name' => 'required|string|min:1|max:100'];
        //Creamos la variable con las validaciones
        $validator = \Validator::make($request->input(),$rules);
        //Si existe un error vamos a retornar un formato JSON con los errores
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
                //Retornamos error 400
            ],400);
        }
        //Si sale bien guarmos en una variable un nuevo departamento con los doatos ingresado por el usuario
        $department = new Department($request->input());
        //Procedemos a guardarlo
        $department->save();
        //Retornamos un estatus 200 para indicar que todo salio bien
        return response()->json([
            'status' => true,
            //Mandamos un mensaje que se creo un departamento satisfactoriamente
            'messege' => 'Department created successfully'
            //Retornamos 200 todo OK
        ],200);
    }

    /**
     * Va a retornar en formato JSON el status de true 
     * y traer la infromacion para mostrar, en la columna status = true y en la columna data la informacion de los de partamentos
     */
    public function show(Department $department)
    {
        return response()->json(['status' => true, 'data' => $department]);
    }

    /**
     * Para actualizar la info de departamento validamos los que los campos cumplan con los requerimientos y si todo marca bien actualizamos la informacion de departamento
     */
    public function update(Request $request, Department $department)
    {
        //Para el campo name vamos a pedir que sea requerido de tipo string con un min de 1 caracter y max de 100 caracteres
        $rules = ['name' => 'required|string|min:1|max:100'];
        //Creamos la variable con las validaciones
        $validator = \Validator::make($request->input(),$rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
                //Retornamos error 400
            ],400);
        }

        //Si todo marca bien actualizamos la informacion
        $department->update($request->all());
        return response()->json([
            'status' => true,
            //Mandamos un mensaje que se creo un departamento satisfactoriamente
            'messege' => 'Department updated successfully'
            //Retornamos 200 todo OK
        ],200);
    }

    /**
     * Para eliminar un registro de departamento mandamos un mensaje de realizado
     */
    public function destroy(Department $department)
    {
        //Eliminamos el registro
        $department->delete();
        return response()->json([
            'status' => true,
            //Mandamos un mensaje que se creo un departamento satisfactoriamente
            'messege' => 'Department deleted successfully'
            //Retornamos 200 todo OK
        ],200);
    }
}
