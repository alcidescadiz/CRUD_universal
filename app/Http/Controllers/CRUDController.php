<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CRUDController extends Controller
{
    // Lista de la base de la datos
    public function database() {
        $tablas= DB::select("SELECT DISTINCT TABLE_SCHEMA FROM INFORMATION_SCHEMA.COLUMNS ORDER BY COLUMNS.TABLE_SCHEMA ASC");
        $atributos = array_values($tablas);
        for ($i=0; $i < count( array_values($tablas)); $i++) { 
            $database[$i]= $atributos[$i]->TABLE_SCHEMA;
        }
        return $database;
    }
    //  en vez de listar todas las bases de datos, puede insertar las BD que desea administrar
    /*public function database() {
            $database =  ['appsialaravel', 'simbiosis'];
        return $database;
    }*/

    // lista de todas las tablas de la base de datos
    public function lista($database) {
        $tablas= DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$database'");
        $atributos = array_values($tablas);
        for ($i=0; $i < count( array_values($tablas)); $i++) { 
            $lista[$i]= $atributos[$i]->TABLE_NAME;
        }
        return $lista;
    }
    
    //  en vez de listar todas las tablas, puede insertar las tablas que desea administrar
    /*public function lista() {
        $lista =  ['ventas', 'compras'];
        return $lista;
    }*/
    
    // cabecera de las tablas
    public function header($nombre, $database) {
        $header = DB::select("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$database' and TABLE_NAME = '$nombre'");
        return $header;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getDB() {
        $database= $this->database();
        return view('tablas.indexDB', ['database'=> $database]);
    }
    public function index(Request $request) {
        $database= $request->get('nombre_bd');
        $tablas= $this->lista($database);
        return view('tablas.index', ['tablas'=>$tablas, 'database'=> $database]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        try {
            $database = $request->get('nombre_bd');
            $nombre = $request->get('nombre_tabla');
            $tablas= $this->lista($database);
            $header = $this->header($nombre, $database );
            $body= DB::select("SELECT * FROM $database.$nombre");
            return view('/tablas/create', ['header'=>$header, 'body'=> $body,'tablas'=>$tablas,'nombre'=> $nombre, 'database'=> $database]);
  
        } catch (\Throwable $th) {
            session()->flash('message', "Error al solicitar ingresar datos nuevos datos a la tabla $nombre"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $nombre = $request->get('nombre_tabla');
            $database = $request->get('nombre_bd');
            $atributos = array_keys($request->all());
            //  $i inicia en 3 para omitir el valor del token , nombre_tabla y nombre_db
            for ($i=3; $i < count($atributos); $i++) { 
                $campos[$i]= $atributos[$i];
            }
            $valores= array_values($request->all());
            for ($i=3; $i < count($valores); $i++) { 
                $datos[$i]= '"'.$valores[$i].'"';
            }
            for ($i=0; $i < count($campos); $i++) { 
                $incognitas[$i]= '?';
            }
            $campos= implode(",", $campos);
            $incognitas= implode(",", $incognitas);
            $datos = implode(",", $datos);
            
            DB::insert("insert into $database.$nombre ($campos) values ($datos)");
    
            return $this->show( $request );

        } catch (\Throwable $th) {
            session()->flash('message', "Error al solicitar ingresar datos nuevos datos a la tabla $nombre"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        try {
            $database = $request->get('nombre_bd');
            $nombre = $request->get('nombre_tabla');
            $header = $this->header($nombre, $database);
            for ($i=0; $i < count($header); $i++) { 
                $datos[$i]= $header[$i]->COLUMN_NAME;
            }
            $campos = implode(", ",$datos);
            //dd($campos);

            $tablas= $this->lista($database);
            $body= DB::table($database.'.'.$nombre)->get();
            return view('tablas.show', [  'campos'=>$campos, 'header'=>$header, 'body'=> $body, 'tablas'=>$tablas, 'nombre'=> $nombre, 'database'=> $database]);
        } catch (\Throwable $th) {
            session()->flash('message', "Error al obtener la tabla $nombre, se produjo el error $th"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->index( $request );
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) {
        try {
            $id = $request->get('id');
            $key_id = $request->get('key_id');
            $nombre = $request->get('nombre_tabla');
            $database = $request->get('nombre_bd');
            $header = $this->header($nombre, $database);
            $tablas= $this->lista($database);
            $body= DB::select("SELECT * FROM $database.$nombre where $key_id = ?", [$id]);
            return view('/tablas/edit', ['header'=>$header, 'body'=> $body,'tablas'=>$tablas,'nombre'=> $nombre, 'id'=>$id, 'database'=> $database]);

        } catch (\Throwable $th) {
            session()->flash('message', "No se puede editar el objeto $id de la tabla $nombre"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        try {
            $nombre = $request->get('nombre_tabla');
            $database = $request->get('nombre_bd');
            $key_id = $request->get('key_id');
            $value_id = $request->get('value_id');
    
            $atributos = array_keys($request->all());
            //  $i inicia en  7 para omitir el valor del token, method, nombre_tabla e  id
            for ($i=7; $i < count($atributos)-1; $i++) { 
                $campos[$i]= $atributos[$i];
            }
            $valores= array_values($request->all());
            for ($i=7; $i < count($valores)-1; $i++) { 
                $datos[$i]= $valores[$i];
            }
            foreach ($datos as $key => $value) {
                $insertar[$key]=  $campos[$key].' = '.'"'.$value.'"';    
            }
            $insertar= implode(",",$insertar);
            DB::update("update $database.$nombre set $insertar where $key_id = ?", [$value_id]);
    
            return $this->show( $request );
        } catch (\Throwable $th) {
            session()->flash('message', "No se logró editar el objeto $value_id de la tabla $nombre, se produjo el error $th"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     // para cambiar un estutus de "eliminado" en un campo establecido ene la tabla
    public function destroy(Request $request) {
        try {
            $nombre = $request->get('nombre_tabla');
            $database = $request->get('nombre_bd');
            $key_id = $request->get('key_id');
            $id = $request->get('id');
            DB::update("update $database.$nombre set estatus = 'eliminado' where $key_id  = ?", [$id]);
            return $this->show( $request );
        } catch (\Throwable $th) {
            session()->flash('message', "No se logró eliminar el objeto $id de la tabla $nombre, es probable que no posea el campo 'estatus'"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }
    
    // Para borrar definitivo
    /*public function destroy(Request $request) {
        try {
            $nombre = $request->get('nombre_tabla');
            $database = $request->get('nombre_bd');
            $key_id = $request->get('key_id');
            $id = $request->get('id');
            DB::delete("delete from $database.$nombre where $key_id = ?", [$id]);
            return $this->show( $request );
        } catch (\Throwable $th) {
            session()->flash('message', "No se logró eliminar el objeto $id de la tabla $nombre"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }*/

    public function consulta(Request $request) {
        try {
            $database = $request->get('nombre_bd');
            $nombre = $request->get('nombre_tabla');
            $consulta = $request->get('consulta');
            $campos = $this->header($nombre, $database);
            for ($i=0; $i < count($campos); $i++) { 
                $datos[$i]= $campos[$i]->COLUMN_NAME;
            }
            $campos = implode(", ",$datos);

            $body= DB::select($consulta);
            $header = array_keys(get_object_vars($body[0]));
            $tablas= $this->lista($database);
            return view('tablas.consulta', [ 'campos'=>$campos, 'header'=>$header, 'body'=> $body, 'tablas'=>$tablas, 'nombre'=> $nombre, 'database'=> $database]);
        } catch (\Throwable $th) {
            session()->flash('message', "Error al obtener en la consulta de tabla $nombre, se produjo el error $th"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }

    }





}
