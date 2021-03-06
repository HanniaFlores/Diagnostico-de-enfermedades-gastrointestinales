<?php 

include_once('load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
    function find_all($table) {
        global $db;
        if(tableExists($table))
        {
        return find_by_sql("SELECT * FROM ".$db->escape($table));
        }else{
            echo "Error en la tabla";
        }
    }

/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_medico='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}


/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
    function find_by_sql($sql)
    {
    global $db;
    $result = $db->query($sql);
    $result_set = $db->while_loop($result);
    return $result_set;
    }



     /*--------------------------------------------------------------*/
    /* Login with the data provided in $_POST,
    /* coming from the login form.
    /*--------------------------------------------------------------*/
    function authenticate($username='', $password='') {
        global $db;
        $username = $db->escape($username);
        $password = $db->escape($password);
        $sql  = sprintf("SELECT id_medico,username_medico,pass_medico, nom_medico,status_medico FROM medicos WHERE username_medico ='%s' LIMIT 1", $username);
        $result = $db->query($sql);
        if($db->num_rows($result)){
        $user = $db->fetch_assoc($result);
        if($password === $user['pass_medico'] ){
            return $user['id_medico'];
        }
        }
    return false;
    }


 /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
    static $current_user;
    global $db;
    if(!$current_user){
       if(isset($_SESSION['user_id'])):
           $user_id = intval($_SESSION['user_id']);
           $current_user = find_by_id('medicos',$user_id);
      endif;
    }
  return $current_user;
}
 

/*--------------------------------------------------------------*/
/* Function for Count id  By table enfermedades
/*--------------------------------------------------------------*/

    function count_by_id_enf($table){
        global $db;
        if(tableExists($table))
        {
        $sql    = "SELECT COUNT(idenfermedades) AS total FROM ".$db->escape($table);
        $result = $db->query($sql);
        return($db->fetch_assoc($result));
        }
    }

/*--------------------------------------------------------------*/
/* Function for Count id  By table sintomas
/*--------------------------------------------------------------*/

function count_by_id_sintomas($table){
    global $db;
    if(tableExists($table))
    {
    $sql    = "SELECT COUNT(idsintomas) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
    return($db->fetch_assoc($result));
    }
}

    /*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
    function tableExists($table){
    global $db;
    $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
        if($table_exit) {
          if($db->num_rows($table_exit) > 0)
                return true;
           else
                return false;
        }
    }


     /*--------------------------------------------------------------*/
    /* Function for Finding all illnesses and symptoms 
    /* JOIN with categorie  database table
    /*--------------------------------------------------------------*/
  function join_illness_has_symptoms_table(){
        global $db;
        $sql  =" SELECT ehs.sintomas_idsintomas, ehs.enfermedades_idenfermedades, e.nombenf ";
        $sql  .=" AS nombre, e.imgenf AS image, s.nombsintoma AS sintoma";
        $sql  .=" FROM enfermedades_has_sintomas ehs";
        $sql  .=" LEFT JOIN sintomas s ON s.idsintomas = ehs.sintomas_idsintomas";
        $sql  .=" LEFT JOIN enfermedades e ON e.idenfermedades = ehs.enfermedades_idenfermedades";
        $sql  .=" ORDER BY ehs.enfermedades_idenfermedades ASC";
        return find_by_sql($sql);

  }

    /*--------------------------------------------------------------*/
    /* Function for Finding all illnesses and medication 
    /* JOIN with tratamientos  database table
    /*--------------------------------------------------------------*/
    function join_illness_medication_table(){
        global $db;
        $sql  =" SELECT e.idenfermedades , e.nombenf, e.origenenf,";
        $sql  .=" e.imgenf , t.desctrat AS tratamiento ";
        $sql  .=" FROM enfermedades e ";
        $sql  .="LEFT JOIN tratamientos t ON t.idtratamientos = e.tratamientos_idtratamientos";
        $sql  .=" ORDER BY e.idenfermedades ASC";
        return find_by_sql($sql);

  }

      /*--------------------------------------------------------------*/
    /* Function for Finding all illnesses and medication 
    /* JOIN with tratamientos  database table by id
    /*--------------------------------------------------------------*/
    function join_illness_medication_id_table($id){
        global $db;
        $sql  =" SELECT e.idenfermedades , e.nombenf, e.origenenf,";
        $sql  .=" e.imgenf , t.desctrat AS tratamiento ";
        $sql  .=" FROM enfermedades e ";
        $sql  .="LEFT JOIN tratamientos t ON t.idtratamientos = e.tratamientos_idtratamientos";
        $sql  .=" WHERE e.idenfermedades = '{$db->escape($id)}' ";
        $sql  .=" ORDER BY e.idenfermedades ASC";
        return find_by_sql($sql);

  }

    /*--------------------------------------------------------------*/
    /* Function for Finding all illnesses and medication 
    /* JOIN with tratamientos  database table by name
    /*--------------------------------------------------------------*/
    function join_illness_medication_name_table($name){
        global $db;
        $sql  =" SELECT e.idenfermedades , e.nombenf, e.origenenf,";
        $sql  .=" e.imgenf , t.desctrat AS tratamiento ";
        $sql  .=" FROM enfermedades e ";
        $sql  .="LEFT JOIN tratamientos t ON t.idtratamientos = e.tratamientos_idtratamientos";
        $sql  .=" WHERE e.nombenf = '{$db->escape($name)}' ";
        $sql  .=" ORDER BY e.idenfermedades ASC";
        return find_by_sql($sql);

  }

       /*--------------------------------------------------------------*/
    /* Function for Finding all illnesses, symptoms 
    /* and medication
    /* JOIN with sintomas y enfermedades  database table
    /*--------------------------------------------------------------*/
    function join_illness_has_symptoms_and_tratamientos_table(){
        global $db;
        $sql  =" SELECT  ehs.valor, ";
        $sql  .=" s.nombsintoma AS sintoma";
        $sql  .=" FROM enfermedades_has_sintomas ehs ";
        $sql  .=" LEFT JOIN sintomas s ON s.idsintomas = ehs.sintomas_idsintomas";
        $sql  .=" LEFT JOIN enfermedades e ON e.idenfermedades = ehs.enfermedades_idenfermedades";
        $sql  .=" ORDER BY ehs.enfermedades_idenfermedades ASC";
        return find_by_sql($sql);

  }


    /*--------------------------------------------------------------*/
    /* Function for Finding all illnesses, symptoms 
    /* and medication especifically
    /* JOIN with sintomas, enfermedades, tratamientos  database table
    /*--------------------------------------------------------------*/
    function join_illness_has_symptoms_and_tratamientos_id_table($id){
        global $db;
        $sql  =" SELECT  ehs.valor, ";
        $sql  .=" s.nombsintoma AS sintoma";
        $sql  .=" FROM enfermedades_has_sintomas ehs ";
        $sql  .=" LEFT JOIN sintomas s ON s.idsintomas = ehs.sintomas_idsintomas";
        $sql  .=" LEFT JOIN enfermedades e ON e.idenfermedades = ehs.enfermedades_idenfermedades";
        $sql  .=" LEFT JOIN tratamientos t ON t.idtratamientos = e.tratamientos_idtratamientos";
        $sql  .=" WHERE e.idenfermedades = '{$db->escape($id)}' ";
        $sql  .=" ORDER BY ehs.enfermedades_idenfermedades ASC";
        return find_by_sql($sql);

  }

      /*--------------------------------------------------------------*/
    /* Function for Finding all symptoms 
    /* JOIN with enfer_has_sintomas database table
    * ORDER BY ehs.id_enf_sint
    /*--------------------------------------------------------------*/

  function join_dictinct_symptoms_table(){
    global $db;
    $sql  =" SELECT DISTINCT s.idsintomas,";
    $sql  .=" s.nombsintoma, ehs.id_enf_sint ";
    $sql  .=" FROM enfermedades_has_sintomas ehs ";
    $sql  .=" LEFT JOIN sintomas s ON s.idsintomas = ehs.sintomas_idsintomas";
    $sql  .=" GROUP BY s.nombsintoma";
    $sql  .=" ORDER BY  ehs.id_enf_sint  ASC";
    return find_by_sql($sql);

}

      /*--------------------------------------------------------------*/
    /* Function for Finding all symptoms 
    /* JOIN with enfer_has_sintomas  database table
    * ORDER BY s.nombsintoma
    /*--------------------------------------------------------------*/

function join_dictinct_symptoms_table2(){
    global $db;
    $sql  =" SELECT DISTINCT s.idsintomas,";
    $sql  .=" s.nombsintoma, ehs.id_enf_sint ";
    $sql  .=" FROM enfermedades_has_sintomas ehs ";
    $sql  .=" LEFT JOIN sintomas s ON s.idsintomas = ehs.sintomas_idsintomas";
    $sql  .=" GROUP BY s.nombsintoma";
    $sql  .=" ORDER BY  s.nombsintoma  ASC";
    return find_by_sql($sql);

}



/** Function for bringing all values of each symptoms
 * by illness
 */
function values_from_symptoms_by_illness_table($enfermedad){
    global $db;
    $sql  ="  SELECT ehs.enfermedades_idenfermedades, s.nombsintoma, ehs.valor";
    $sql  .=" FROM enfermedades_has_sintomas ehs ";
    $sql  .=" LEFT JOIN sintomas s ON s.idsintomas = ehs.sintomas_idsintomas";
    $sql  .=" LEFT JOIN enfermedades e ON e.idenfermedades = ehs.enfermedades_idenfermedades";
    $sql  .=" WHERE e.nombenf = '{$db->escape($enfermedad)}'";
    $sql  .=" group by s.nombsintoma";
    return find_by_sql($sql);

}


/*** Function for getting all values of each symptom without repeat it 
 * by illnesses
 */
function values_each_symptom_by_illness_table(){
    global $db;
    $sql  =" SELECT s.nombsintoma,
				max(case 
				WHEN e.nombenf = 'Salmonelosis'
                then ehs.valor
                ELSE 0
                END) as 'Salmonelosis',
                max(case 
				WHEN e.nombenf = 'Apendicitis'
                then ehs.valor
                ELSE 0
                END) as 'Apendicitis',
                max(case 
				WHEN e.nombenf = 'Cancer Gastrico'
                then ehs.valor
                ELSE 0
                END) as 'Cancer Gastrico',
                max(case 
				WHEN e.nombenf = 'Parasitosis'
                then ehs.valor
                ELSE 0
                END) as 'Parasitosis',
                max(case 
				WHEN e.nombenf = 'Diarrea'
                then ehs.valor
                ELSE 0
                END) as 'Diarrea',
                max(case 
				WHEN e.nombenf = 'Dispepsia'
                then ehs.valor
                ELSE 0
                END) as 'Dispepsia',
                max(case 
				WHEN e.nombenf = 'Gastritis'
                then ehs.valor
                ELSE 0
                END) as 'Gastritis',
                max(case 
				WHEN e.nombenf = 'Gastroenteritis'
                then ehs.valor
                ELSE 0
                END) as 'Gastroenteritis',
                max(case 
				WHEN e.nombenf = 'Intolerancia a la lactosa'
                then ehs.valor
                ELSE 0
                END) as 'Intolerancia a la lactosa',
                max(case 
				WHEN e.nombenf = 'Intolerancia al Gluten'
                then ehs.valor
                ELSE 0
                END) as 'Intolerancia al Gluten'
                ";
    $sql  .=" FROM enfermedades_has_sintomas ehs ";
    $sql  .=" LEFT JOIN sintomas s ON s.idsintomas = ehs.sintomas_idsintomas ";
    $sql  .=" LEFT JOIN enfermedades e ON e.idenfermedades = ehs.enfermedades_idenfermedades ";
    $sql  .=" GROUP BY s.nombsintoma";

    return find_by_sql($sql);
}


?>