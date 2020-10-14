<?php
require_once 'models/CargoAutoridad.php';
require_once 'models/Cargo.php';
require_once 'models/Autoridad.php';

class CargoAutoridadController {
	
	public function index(){
		
		$cargo_autoridad = new CargoAutoridad();

		$result = $cargo_autoridad->getAllCargoAutoridad();

		echo json_encode($result);
	}
	
	public function store(){
		$cargo_autoridad = new CargoAutoridad();

		$cargo_autoridad->setIdCargo($_POST['idcargo']);
		$cargo_autoridad->setIdAutoridad($_POST['idautoridad']);
		$cargo_autoridad->setFechaInicio($_POST['fecha_inicio']);
		$cargo_autoridad->setFechaFin($_POST['fecha_fin']);

		$result = $cargo_autoridad->insertar();

		echo json_encode($result);
	}
	
	public function update(){
		$cargo_autoridad = new CargoAutoridad();

		$cargo_autoridad->setId($_POST['id']);
		$cargo_autoridad->setIdCargo($_POST['idcargo']);
		$cargo_autoridad->setIdAutoridad($_POST['idautoridad']);
		$cargo_autoridad->setFechaInicio($_POST['fecha_inicio']);
		$cargo_autoridad->setFechaFin($_POST['fecha_fin']);
		
		$result = $cargo_autoridad->actualizar();

		echo json_encode($result);
	            
	}
	
	public function activar(){
		$cargo_autoridad = new CargoAutoridad();

		$cargo_autoridad->setId($_POST['id']);

		$result = $cargo_autoridad->activar();

		echo json_encode($result);
	}	
	
	public function desactivar(){
	    $cargo_autoridad = new CargoAutoridad();

		$cargo_autoridad->setId($_POST['id']);      

		$result = $cargo_autoridad->desactivar();

		echo json_encode($result);   
	}	

	public function readCargo(){
		$cargo = new Cargo();

      	$result = $cargo->getActives();

      	echo json_encode($result);          
	}

	public function readAutoridad(){
		$autoridad = new Autoridad();

      	$result = $autoridad->getActives();

      	echo json_encode($result);          
	}
}