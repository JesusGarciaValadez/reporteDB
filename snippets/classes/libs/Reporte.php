<?php

class Reporte extends Model {
    
    protected $_PDOConn = null;
    
    public function __construct( $conn )
    {
        $this->_tableName = 'answers';
        $this->_primaryKey = 'id';
        $this->setMysqlConn( $conn );
        $this->_PDOConn = $conn;
    }
    
    public function obtenerCabecera ( )
    {
        $this->setTableName ( '`questions` AS q' );
        
        $fields     = array(
            'q.`question` AS Question'
        );
        $conditions = array(
            'order'         => 'Question',
            'orderSense'    => 'ASC'
        );
        $table = '';
        
        $resultSet  = $this->select( $fields, $conditions );
        $table = $this->_generarCabecera( $resultSet );
        return $table;
    }
    
    public function obtenerReporte ( )
    {
        $table      = $this->obtenerCabecera();
        
        $this->setTableName ( '`users` AS u, `answers` AS a' );
        
        $fields     = array(
            'a.`answers` AS Answers',
            '( SELECT u.`id_user` FROM `users` AS u WHERE u.`id_user` = a.`id_user` ) AS ID_User',
            '( SELECT u.`nombre` FROM `users` AS u WHERE u.`id_user` = a.`id_user` ) AS Nombre',
            '( SELECT u.`username` FROM `users` AS u WHERE u.`id_user` = a.`id_user` ) AS Username'
        );
        $conditions = array(
            'where'         => 'a.`id_user` = u.`id_user`',
            'order'         => 'ID_User',
            'orderSense'    => 'ASC'
        );
        
        $resultSet  = $this->select( $fields, $conditions );
        
        if ( $this->getNumRows() ) {
            
            echo $table;
            echo $this->_generarRepote($resultSet);
        }
        return $table;
    }
    
    private function _generarCabecera ( $data )
    {
        $head = '<table>
            <thead>
                <tr>
                    <th class="column_name">ID_Usuario</th>
                    <th class="column_date">Nombre</th>
                    <th class="column_agent">Username</th>';
        foreach( $data as $question ){
            $head .= "<th class=\"column_business\">{$question['Question']}</th>";
        }
        $head .= '</tr>
            </thead>';
        
        return $head;
    }
    
    private function _generarRepote ( $data )
    {
        $body = '<tbody>';
        
        foreach ( $data as $answers ) {
            $body .= "<tr>
                    <td class=\"column_id\">{$answers['ID_User']}</td>
                    <td class=\"column_name\">{$answers['Nombre']}</td>
                    <td class=\"column_username\">{$answers['Username']}</td>
                    <td class=\"column_answer\">{$answers['Answers']}</td>";
                /*foreach( $answers['Answers'] as $respuesta ){
                    $body   .= "<td class=\"column_answer\">{$Respuesta['Answers']}</td>";
                }*/
            $body .= "</tr>";
        }
        
        $footer     = '</tbody></table>';
        $tableBody  = $body . $footer;
        return $tableBody;
    }
}