<?php
require_once 'models/Expediente.php';

class ExpedienteController {
	
	public function getList() {		
		$idprocedimiento = $_POST['idprocedimiento'];
                $codi_usuario = $_POST['codi_usuario'];                
                $tipo_rol = $_POST['tipo_rol'];
                $expediente = new Expediente();
                $result = $expediente->getList($idprocedimiento, $codi_usuario, $tipo_rol);
        
                echo json_encode($result);
	}
	
	public function getListByCodi() {
		$codi_usuario = $_GET['codi_usuario'];  
                $expediente = new Expediente();        
                $result = $expediente->getListByCodi($codi_usuario);
        
                echo json_encode($result); 
	}
	
	public function getMovByIdExp() {
                $idexpediente = $_GET['idexpediente'];        
                $expediente = new Expediente();        
                $result = $expediente->getMovByIdExp($idexpediente);
                
                echo json_encode($result);
	}
	
	public function getExpById() {        
                $expediente = new Expediente();                
                $expediente->setId($_POST['idexpediente']);                         
                $result = $expediente->getExpediente();
        
                echo json_encode($result);  
        }       

        public function getURL() {        
                $expediente = new Expediente();                
                $expediente->setId($_POST['idexpediente']);                                                                
                $result = $expediente->getURL();
        
                echo json_encode($result);  
	}
        
        public function updateURL() {        
                $expediente = new Expediente();                
                $expediente->setId($_POST['idexpediente']);                         
                $expediente->setUrlRepo($_POST['url_repo']);                         

                $result = $expediente->actualizar_url();
        
                echo json_encode($result);  
        }
        
        public function upd_tp_st_acta_dictamen() {
                $expediente = new Expediente();                
                $expediente->setId($_POST['idexpediente']);   
                $expediente->setFechaSesionJurado($_POST['fecha_sesion_jurado']);                                                                
                $expediente->setFechaSustentacion($_POST['fecha_sustentacion']);                    
                $expediente->setHoraSustentacion($_POST['hora_sustentacion']);
                $result = $expediente->upd_tp_st_acta_dictamen();
        
                echo json_encode($result);
        }
}