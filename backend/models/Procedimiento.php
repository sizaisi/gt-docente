<?php

class Procedimiento {
	private $id;
	private $idgrado_modalidad;
    private $idprocedimiento;
    private $idrol;
    private $tipo_rol; 
    private $url;
    private $orden;
    private $descripcion;

    private $conn;
	
	public function __construct() {
		$this->conn = Database::conectar();
	}
	
	function getId() {
		return $this->id;
	}

	function setId($id) {
		$this->id = $id;
	}

	function getIdGradoModalidad() {
		return $this->idgrado_modalidad;
	}

	function setIdGradoModalidad($idgrado_modalidad) {
		$this->idgrado_modalidad = $idgrado_modalidad;
	}	

	function getIdProcedimiento() {
		return $this->idprocedimiento;
	}

	function setIdProcedimiento($idprocedimiento) {
		$this->idprocedimiento = $idprocedimiento;
	}

	function getIdRol() {
		return $this->idrol;
	}

	function setIdRol($idrol) {
		$this->idrol = $idrol;
	}

	function getTipoRol() {
		return $this->tipo_rol;
	}

	function setTipoRol($tipo_rol) {
		$this->tipo_rol = $tipo_rol;
	}

	function getUrl() {
		return $this->url;
	}

	function setUrl($url) {
		$this->url = $url;
	}

	function getOrden() {
		return $this->orden;
	}

	function setOrden($orden) {
		$this->orden = $orden;
    }
    
    function getDescripcion() {
		return $this->descripcion;
	}

	function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}   

    public function getProcedimientos($codi_usuario) {
        $result = array('error' => false);            
        
        $sql = "SELECT GT_P.*, GT_P.id AS idproc, COUNT(GT_E.id) AS total_expedientes,
                GT_P.nombre, GT_P.descripcion
                FROM gt_procedimientos AS GT_P                        
                INNER JOIN gt_expediente AS GT_E ON GT_E.idprocedimiento = GT_P.id                    
                WHERE GT_P.deleted_at IS NULL AND GT_P.idgradomodalidad = $this->idgrado_modalidad 
                AND GT_P.idrol =  $this->idrol
                AND GT_E.id IN (SELECT R.idexpediente
                                FROM gt_recurso AS R
                                    INNER JOIN gt_persona AS P ON P.idrecurso = R.id
                                    INNER JOIN gt_usuario AS U ON U.id = P.iddocente
                                WHERE IF(GT_P.tipo_rol='asesor', P.tipo='asesor', P.tipo IN ('presidente', 'secretario', 'suplente')) 
                                    AND P.estado = 1  
                                    AND U.codi_usuario='$codi_usuario')                    
                GROUP BY GT_P.id 
                ORDER BY GT_P.id ASC";               		      

        $result_query = mysqli_query($this->conn, $sql);

        $array_procedimiento = array();

        while ($row = $result_query->fetch_assoc()) {
            array_push($array_procedimiento, $row);
        }

        $result['array_procedimiento'] = $array_procedimiento;        

        return $result;
    }

    public function getListByIdGradoModalidad() { //obtener todos los grado procedimientos por idgradomodalidad

        $result = array('error' => false);        
        
        $sql = "SELECT gt_gp.id AS idgradoproc, gt_p.nombre AS proc_nombre
                FROM gt_grado_procedimiento AS gt_gp
                INNER JOIN gt_procedimiento AS gt_p ON gt_gp.idprocedimiento = gt_p.id
                WHERE gt_gp.condicion = 1 AND gt_gp.idgrado_modalidad = $this->idgrado_modalidad
                ORDER BY gt_p.nombre ASC";

        $result_query = mysqli_query($this->conn, $sql);

        $array_grado_procedimiento = array();

        while ($row = $result_query->fetch_assoc()) {
            array_push($array_grado_procedimiento, $row);
        }

        $result['array_grado_procedimiento'] = $array_grado_procedimiento;

        return $result;
    }    
}