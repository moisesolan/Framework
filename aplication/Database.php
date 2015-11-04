<?php

/**
	 * Esta es la clase PDO
	 * 
	 * Esta clase sirve para realizar la conexion a nuestra base de datos
	 * 
	 * @author Moises Olan Gonzalez <itic722014@gmail.com>
	 * @version 1.0 Mi primera versión
	 * @package Mi Framework
	 * Clase PDO para la conexion a la base de datos
	 * @var  $conection guarda la conexion a nuestra BD
	 * @var  $dsn se encarga de guardar la cadena para conexión
	 * @var  $drive contiene el controlador de nuestra BD
	 * @var  $host contiene los datos de nuestro servidor donde esta alojada la BD
	 * @var  $database contiene el nombre de la BD
	 * @var  $username contiene nombre de usuario de la BD
	 * @var  $password contiene el password de la BD
	 * @var  $result contiene el resutado de la conexión
	 * @var  $lasInsertId contiene el ultimo registro insertado a nuestra BD
	 * @var  $number_Rows contiene el nuero de filas afectadas
*/

	class classPDO
	{
		public $connection;
		private $dsn;
		private $drive;
		private $host;
		private $database;
		private $username;
		private $password;
		public $result;
		public $lastInsertId;
		public $numbers_Rows;

		public function __construct(
				$drive 	= 	'mysql',
				$host 	=	'localhost',
				$database =	'gestion',
				$username =	'root',
				$password =	''
			){
			$this->drive 		= $drive;
			$this->host 		= $host;
			$this->database 	= $database;
			$this->username 	= $username;
			$this->password 	= $password;
			$this->connection();
		}

		/**
		 *  
		 * connection aqui realizamos la conexión a nuestra BD
		 * @return void
		 * 
		 */
		public function connection(){
			$this->dsn = $this->drive.':host='.$this->host.';dbname='.$this->database;

			try{
				$this->connection = new PDO(
						$this->dsn,
						$this->username,
						$this->password
					);
				$this->connection->setAttribute(
						PDO::ATTR_ERRMODE,
						PDO::ERRMODE_EXCEPTION
					);
			}catch(PDOException $e){
				echo "ERROR: ".$e->getMessage();
				die();
			}
		}

/** 
		 * find funcion para realizar consultas tipo select a la BD
		 * @param  string $table nombre de la tabla
		 * @param  string $query almacena los tipos de busqueda
		 * @param  array $options contiene todas las condiciones de la consulta
		 * @return string $result resultado de la consulta
		 * @param  string $fields contiene la cantidad de campos a seleccionar en la consulta
		 * @param  string $parameters contiene los parametros de la consulta
		 * 
*/		public function find($table = null, $query = null, $options = array()){
			$fields = '*';
			$parameters = '';


			if (!empty($options['field'])) {
				$fields = $options['field'];
			}

			if (!empty($options['conditions'])) {
				$parameters = ' WHERE ' .$options['conditions'];
			}

			if (!empty($options['group'])) {
				$parameters .= ' GROUP BY ' .$options['group'];
			}

			if (!empty($options['order'])) {
				$parameters .= ' ORDER BY ' .$options['order'];
			}

			if (!empty($options['limit'])) {
				$parameters .= ' LIMIT ' .$options['limit'];
			}

			switch ($query) {
				case 'all':
					$sql = "SELECT $fields FROM $table".' '.$parameters;
					$this->result = $this->connection->query($sql);
					break;
				case 'count':
					$sql = "SELECT COUNT(*) FROM $table".' '.$parameters;
					$result = $this->result = $this->connection->query($sql);
					$this->result = $result->fetchColumn();
					break;
				case 'first':
					$sql = "SELECT $fields FROM $table".' '.$parameters;
					$result = $this->result = $this->connection->query($sql);
					$this->result = $result->fetch();

					break;
				
				default:
					$sql = "SELECT $fields FROM $table".' '.$parameters;
					$this->result = $this->connection->query($sql);
					break;
			}
			return $this->result;
		}




		/**
		 *  
		 * Este es el metodo para insertar datos a la BD
		 * @param  string $table contiene nombre de la tabla
		 * @param  array $data contiene los datos a insertar
		 * @param  string $sql contiene la instruccion de inserción
		 * @return objeto $result reultado de la consulta 
		 * 
		 */
		
		public function save($table = null, $data = array()){
			//obtener el numero de columnas
			$sql = "SELECT * FROM $table";
			$result = $this->connection->query($sql);

			for ($i=0; $i < $result->columnCount(); $i++) { 
				$meta = $result->getColumnMeta($i);
				$fields[$meta['name']] = null;
			}

			$fieldsToSave = 'id';
			$valueToSave = 'NULL';

			foreach ($data as $key => $value) {
				if (array_key_exists($key, $fields)) {
					$fieldsToSave .= ', ' .$key;
					$valueToSave  .= ', '."\"$value\"";				
				}
			}
		

		$sql = "INSERT INTO $table ($fieldsToSave) 
		VALUES ($valueToSave);";

		$this->result = $this->connection->query($sql);

		return $this->result;

		}



		/**
		 * la siguiente función update sirve para actualizar los registros en la BD
		 * 
		 * @param  string $table contiene nombre de la tabla
		 * @param  array $data datos que se van a actualizar
		 * @var    string $sql es la instruccion sql
		 * @return objeto retorna el resultado de la consulta
		 */
		
		
	public function update($table = null, $data = array()){
		$sql = "SELECT * FROM $table";
			$result = $this->connection->query($sql);

			for ($i=0; $i < $result->columnCount(); $i++) { 
				$meta = $result->getColumnMeta($i);
				$fields[$meta['name']] = null;
			}
			if (array_key_exists("id", $data)) {
				$fieldsToSave = "";
				$id = $data['id'];
				unset($data['id']);

				foreach ($data as $key => $value) {
					if (array_key_exists($key, $fields)){
						$fieldsToSave .= $key."="."\"$value\", ";
					}
				}
				$fieldsToSave = substr_replace($fieldsToSave, "", -2);
				$sql = "UPDATE $table SET $fieldsToSave WHERE $table.id=$id;";
			}	
			$this->result = $this->connection->query($sql);
			return $this->result;

	}

	/**
	 * 
	 * la siguiente funcion sirve para eliminar registros de nuestra BD
	 * @param  string $table      Contiene nombre de la tabla
	 * @param  string $conditions tiene las condiciones de la consulta 
	 * @return objeto $result     tiene el resultado de la consulta realizada
	 *
	 */
	
	public function delete($table = null, $conditions){
		$sql = "DELETE FROM $table WHERE $conditions".";";
		$this->result = $this->connection->query($sql);

		$this->numberRows = $this->result->rowCount();
		return $this->result;
	}

}

/**
 * 
 * @var db crea una instancia de la clase PDO
 *
 */

$db = new classPDO();




?>