<?php
class Classes {
    const CLASS_TYPE_DAY = '';

    public function add() {
        Input::all();
    }

    public function edit() {

    }

    public function delete() {

    }

    /**
     * id, [parentid, level]
     * @param $params
     */
    public function find($params, $level=1) {
        $query = " 1=1 ";

        foreach($params as $k=>$v) {
            switch($k) {
                case 'id':
                    $query .= " AND id = {$params['id']}";
                break(2);

                case 'parentid':
                    $query .= " AND parentid = {$params['parentid']} ";
                break(2);
            }
        }

        $sql = "SELECT id, title, code FROM tb_class WHERE {$query}";
        return $this->select($sql);
    }
}