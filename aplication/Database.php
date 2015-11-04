<?php
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

		//CREACION DEL METODO FIND
		public function find($table = null, $query = null, $options = array()){
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




		//CREACION DEL METODO SAVE
		
		public function save($table = null, $data = array()){
			//obtener el numero de columnas
			$sql = "SELECT * FROM $table";
			$result = $this->connection->query($sql);

			for ($i=0; $i < $result->columnCount(); $i++) { 
				$meta = $result->getColumnMeta($i);
				$fields[$meta['name']] = null;
			}

			//CONVECION DE NOMBRE, TABLAS EN PLURAL Y CAMPOS EN SINGULAR EN INGLES DE PREFERENCIA CON LA LLAVE PRIMARIA LAMADA ID NULL AUTOINC
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



		//CREACION DEL METODO UPDATE
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



	//CREACION DEL METODO DELETE
	
	public function delete($table = null, $conditions){
		$sql = "DELETE FROM $table WHERE $conditions".";";
		$this->result = $this->connection->query($sql);

		$this->numberRows = $this->result->rowCount();
		return $this->result;
	}

}



$db = new classPDO();




?>