<?php

class IdeasController extends Controller {
	
	public function index($order = null)
	{
		$data['ideas'] = Idea::where('display', 1)->get();
		$this->render('ideas/index', $data);
	}

	public function idea($id = null)
	{
		$data['idea'] = Idea::find($id);
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
				$p = $this->post();

				$idea = new Idea();
				$idea->title = $p['title'];
				$idea->content = $p['idea'];
				$idea->user_id = $this->user->id;
				$idea->ip_address = $req->getIp();
	            $idea->display = DISPLAY_OPTION;
                $idea->save();
                $id = $idea->id;

				if ($id) {
					$this->successFlash(sprintf('You have successfully saved %s', $id));
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