<?php/////////////////////////////////////////////////////////////////////////////////// Licensed Source Code - Property of f-project.net//// © Copyright f-project.net 2013. All Rights Reserved./////////////////////////////////////////////////////////////////////////////////class IssueController extends Controller{    /* ************************************************************************     *     * Gii Generated properties and methods     *     *********************************************************************** */	/**	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning	 * using two-column layout. See 'protected/views/layouts/column2.php'.	 */	public $layout='//layouts/column2';	/**	 * @return array action filters	 */	public function filters()	{		return array(			'accessControl', // perform access control for CRUD operations			'postOnly + delete', // we only allow deletion via POST request            'projectContext + create', //20130531 Added : Check to ensure valid project context		);	}	/**	 * Displays a particular model.	 * @param integer $id the ID of the model to be displayed	 */	public function actionView($id)	{		$this->render('view',array(			'model'=>$this->loadModel($id),		));	}	/**	 * Creates a new model.	 * If creation is successful, the browser will be redirected to the 'view' page.	 */	public function actionCreate()	{		$model=new Issue;        $model->projectId = $this->_project->id;//20130601 Added : Apply project context		// Uncomment the following line if AJAX validation is needed		// $this->performAjaxValidation($model);		if(isset($_POST['Issue']))		{			$model->attributes=$_POST['Issue'];			if($model->save())				$this->redirect(array('view','id'=>$model->id));		}		$this->render('create',array(			'model'=>$model,		));	}	/**	 * Updates a particular model.	 * If update is successful, the browser will be redirected to the 'view' page.	 * @param integer $id the ID of the model to be updated	 */	public function actionUpdate($id)	{		$model=$this->loadModel($id);		// Uncomment the following line if AJAX validation is needed		// $this->performAjaxValidation($model);		if(isset($_POST['Issue']))		{			$model->attributes=$_POST['Issue'];			if($model->save())				$this->redirect(array('view','id'=>$model->id));		}		$this->render('update',array(			'model'=>$model,		));	}	/**	 * Deletes a particular model.	 * If deletion is successful, the browser will be redirected to the 'admin' page.	 * @param integer $id the ID of the model to be deleted	 */	public function actionDelete($id)	{		$this->loadModel($id)->delete();		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser		if(!isset($_GET['ajax']))			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));	}	/**	 * Lists all models.	 */	public function actionIndex()	{		$dataProvider=new CActiveDataProvider('Issue');		$this->render('index',array(			'dataProvider'=>$dataProvider,		));	}	/**	 * Manages all models.	 */	public function actionAdmin()	{		$model=new Issue('search');		$model->unsetAttributes();  // clear any default values		if(isset($_GET['Issue']))			$model->attributes=$_GET['Issue'];		$this->render('admin',array(			'model'=>$model,		));	}	/**	 * Returns the data model based on the primary key given in the GET variable.	 * If the data model is not found, an HTTP exception will be raised.	 * @param integer $id the ID of the model to be loaded	 * @return Issue the loaded model	 * @throws CHttpException	 */    //20130601 Modified : Apply project context	public function loadModel($id)	{        $model = $this->findModelById($id);        if ($model === null)            throw new CHttpException(404, 'The requested page does not exist.');        $this->_project = $model->project;        return $model;	}    /**     * Find the model by its ID number     * @param integer $id the primary identifier of the associated model     * @return Issue the return model     */    private function findModelById($id)    {        return User::model()->findByPk($id);    }	/**	 * Performs the AJAX validation.	 * @param Issue $model the model to be validated	 */	protected function performAjaxValidation($model)	{		if(isset($_POST['ajax']) && $_POST['ajax']==='issue-form')		{			echo CActiveForm::validate($model);			Yii::app()->end();		}	}    /* ************************************************************************     *     * f-project.net implementation properties and methods are after this block     *     *********************************************************************** */    /**     * @var Project Private property containing the associated Project model     * instance.     */    private $_project = null;    /**     * Protected method to load the associated Project model class     * @param integer $projectId the primary identifier of the associated Project     * @throws CHttpException     * @return Project the Project data model based on the primary key     */    protected function loadProject($projectId)    {        //if the project property is null, create it based on input id        if ($this->_project === null) {            $this->_project = Project::model()->findByPk($projectId);            if ($this->_project === null) {                throw new CHttpException(404, 'The requested project does not exist.');            }        }        return $this->_project;    }    /**     * In-class defined filter method, configured for use in the above filters()     * method. It is called before the actionCreate() action method is run in     * order to ensure a proper project context     * @param $filterChain     * @throws CHttpException     * @internal param $     */    public function filterProjectContext($filterChain)    {        //set the project identifier based on GET input request variables        if (isset($_GET['pid']))            $this->loadProject($_GET['pid']);        else            throw new CHttpException(403, 'Must specify a project before performing this action.');        //complete the running of other filters and execute the requested action        $filterChain->run();    }    /**     * @return array Array of valid users for this project, indexed by user IDs     */    public function getUserListData()    {        $usersArray = CHtml::listData($this->_project->users, 'id', 'username');        return $usersArray;    }}