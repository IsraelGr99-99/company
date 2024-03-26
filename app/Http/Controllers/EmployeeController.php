<?php

namespace App\Http\Controllers;

//Importamos el modelo de departamentos
use App\Models\Department;
// importamos DB
use DB;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * Traera toda la información de los empleados
     */
    public function index()
    {
        //Traemos toda la info de la tabla employees y nombramos el nombre del departamento como departamento
        $employees = Employee::select(
            'employees.*',
            'departments.name as department'
        )
            //Unimos la tabla de departamento con la de empleados mediante los ID 
            ->join(
                'departments',
                'departments.id',
                '=',
                'employees.department_id'
            )
            //Lo retornamos con una paginación de 10
            ->paginate(10);
        //Retornamos en formato JSON la informacion de los empleados
        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     * Creamos la reglas para poder ser almacenadas en la BD
     * y Creamos lo registros
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:1|max:100',
            'email' => 'required|email|max:80',
            'phone' => 'required|max:15',
            'department_id' => 'required|numeric',
        ];
        //Creamos la variable validator para validar lo ingredado por el usuario y las reglas
        $validator = \Validator::make($request->input(), $rules);

        //Si hay algun error vamos a retornar un formato JSON con los errores causados
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->all(),
                //Indicamos el error 400
            ],400);
        }

        //Si todo marcha bien vamos a crear un nuevo registro de empleados con todos los request
        $employee = new Employee($request->input());
        //Save para guardar el registro
        $employee->save();
        //Retornamos el estatus 200 indicando que todo salio bien
        return response()->json([
            'status' => true,
            'message' => 'Employee created successfully'
            //Indicamos el error 400
        ],200);
    }

    /**
     * Display the specified resource.
     * Mostrar info empleados
     */
    public function show(Employee $employee)
    {
        return response()->json(['status' => true, 'data' => $employee]);
    }

    /**
     * Update the specified resource in storage.
     * Actualizar registros
     */
    public function update(Request $request, Employee $employee)
    {
        $rules = [
            'name' => 'required|string|min:1|max:100',
            'email' => 'required|email|max:80',
            'phone' => 'required|max:15',
            'department_id' => 'required|numeric',
        ];
        //Creamos la variable validator para validar lo ingredado por el usuario y las reglas
        $validator = \Validator::make($request->input(), $rules);

        //Si hay algun error vamos a retornar un formato JSON con los errores causados
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->all(),
                //Indicamos el error 400
            ],400);
        }
        //Si todo marcha bien actualizamos el registro con la informacion del request
        $employee->update($request->input());
        //Retornamos la respuesta en formato JSON con el estado 200 indicando que todo salio bien
        return response()->json([
            'status'=> true,
            'message'=> 'Employee updated successfully'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     * Eliminar empleado
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json([
            'status'=> true,
            'message'=> 'Employee deleted successfully'
        ],200);
    }
    /**
     * Esta funcion estara contando cuantos empleados hay por departamentos
     */
    public function EmployeesByDepartment(Request $request){
        //Contamos con la funcion COUNT los empleados por ID y los guardamos como cuenta ademas mostraremos el nombre del departamento
        $employees = Employee::select(DB::raw('count(employees.id) as count,
        departments.name'))
        //Lo unimos con la tabla de departamentos
        ->rightjoin('departments','departments.id','=','employees.department_id')
        //Agrupamos todo con el nombre de departamentos
        ->groupBy('departments.name')->get();
        //Retornamos toda la inf de los empleados en un formato JSON  
        return response()->json($employees);  
    }
    /**
     * Creamos funcion para traer la informacion de todos los empleados
     */
    public function all(){
        //Traemos toda la info de la tabla employees y nombramos el nombre del departamento como departamento
        $employees = Employee::select(
            'employees.*',
            'departments.name as department'
        )
            //Unimos la tabla de departamento con la de empleados mediante los ID 
            ->join(
                'departments',
                'departments.id',
                '=',
                'employees.department_id'
            );
        return response()->json($employees);
    }
    
}
