<?php

	/**
 * @author Moises Olan Gonzalez <itic722014@gmail.com>
 * @version 1.0 Mi primera versión
 * @package Mi Framework
 * 
 *
 */

	class categoriasController extends Appcontroller{

		public function __construct(){
		parent::__construct();
	}

/**
	 * index muestra un listado de categorias existentes
	 * @return void
*/


	public function index() {
		//echo "Hola desde el metodo index";
		
		$this->_view->titulo = 'Listado de categorias';
		$this->_view->categorias = $this->db->find('categorias', 'all');
		$this->_view->renderizar('index');
	}

		/**
		 * la funcion edit sirve para la edicion de categorias
		 * @param  string $id contiene el id de la fila
		 * @return void
		 */

		public function edit($id = null){

		if ($_POST) {
				
				if ($this->db->update('categorias', $_POST)) {
				$this->redirect(
						array(
								'controller'=>'categorias',
								'action'=>'index'
							)
					);
			}else{
				$this->redirect(
						array(
								'controller'=>'categorias',
								'action'=>'edit/'.$_POST['id']
							)
					);
			}


		}else{
		$conditions = array(
				'conditions'=>'id='.$id
			);
		$this->_view->categoria = $this->db->find('categorias', 'first', $conditions);
		$this->_view->titulo = "Editar Categoría";
		$this->_view->renderizar('edit');
		}

	}


	/**
		 * la funcion add sirve para agregar nuevas categorias
		 * @return  void
		 * 
	*/

		public function add(){
		if ($_POST) {
			if ($this->db->save('categorias', $_POST)) {
				$this->redirect(
						array(
								'controller'=>'categorias',
								'action'=>'index'
							)
					);
			}else{
				$this->redirect(
						array(
								'controller'=>'categorias',
								'action'=>'index'
							)
					);
			}
			
		}else{
			$this->_view->titulo = "Agregar Categoría";
			$this->_view->renderizar('add');
		}

	}

	/**
		 * la funcion delete sirve para eliminar alguna categoria existente
		 * @param  string $id contiene id de la fila
		 * @return void
		 */

		public function delete($id){
		$conditions = "id=".$id;
		if ($this->db->delete('categorias', $conditions)) {
			$this->redirect(array('controller'=>'categorias'));
		}

	}



}




?>