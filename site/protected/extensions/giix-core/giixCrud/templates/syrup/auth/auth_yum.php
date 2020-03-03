    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions'=>array(),
                'roles'=>array(''),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
