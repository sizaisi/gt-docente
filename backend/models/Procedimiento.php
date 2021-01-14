<?php

class Procedimiento {
	private $id;
	private $idtramite;
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

	function getIdTramite() {
		return $this->idtramite;
	}

	function setIdTramite($idtramite) {
		$this->idtramite = $idtramite;
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
                WHERE GT_P.deleted_at IS NULL AND GT_P.idtramite = $this->idtramite
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
}