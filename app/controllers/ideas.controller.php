<?php

class IdeasController extends Controller {
	
	public function index($order = null)
	{
		$data['ideas'] = R::find('ideas', ' display = :display ORDER BY id', array(
                            ':display' => 1
                        ));
		$this->render('ideas/index', $data);
	}

	public function idea($id = null)
	{
		$data['idea'] = R::findOne('ideas', ' id=?', array($id));
		$data['singleView'] = true;
		$this->render('ideas/single', $data);
	}

	public function save()
	{
		$req = $this->app->request();

		if ($req->isPost()) {
			$v = $this->validator($this->post());
			$v->rule('required', array('title', 'idea'));

			if ($v->validate()) {
				$p = $req->post();
				$idea = R::dispense('ideas');
				$idea->title = $p['title'];
				$idea->content = $p['idea'];
				$idea->user_id = $this->user['id'];
				$idea->ip_address = $req->getIp();
	            $idea->createdon = R::isoDateTime();
	            $idea->display = DISPLAY_OPTION;
	            $id = R::store($idea);
				if ($id) {
					$this->app->flash('info', sprintf('You have successfully saved %s', $id));
					$this->redirect('home');
				}
				$this->app->flashNow('error', 'Your idea was not saved.');
			}
			$this->app->flash('formError', $this->errorOutput($v->errors(), true));
			$this->redirect('home');
		}

		$this->render('ideas/idea');
	}

	public function allIdeas()
	{
		$photos = array(
			'James' => array('images/home.jpg', 'images/about.jpg'),
			'John' => array('images/dash.jpg', 'images/name.jpg'),
		);

		$this->response($photos);
	}
}