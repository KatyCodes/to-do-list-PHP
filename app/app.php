<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/List.php";

//Creates empty array if it doesnt exist
    session_start();
    if (empty($_SESSION['list_of_tasks'])) {
      $_SESSION['list_of_tasks'] = array();
    }

//notifys Silex exists
    $app = new Silex\Application();

//notifying twig exists
    $app->register(new Silex\Provider\TwigServiceProvider(), array (
      'twig.path' => __DIR__.'/../views'
    ));

//creates home pages
    $app->get("/", function() use ($app) {
      return $app['twig']->render('tasks.html.twig', array('tasks' =>
      Task::getAll()));
    });

//Appends user input from form into /tasks page
//$_POST['this is where form info is pulled by it's name]
    $app->post("/tasks", function() use ($app) {
      $task = new Task($_POST['description']);
      $task->save();
      return $app['twig']->render('create_task.html.twig', array('newtask' => $task));
    });

//Sends user to page /delete_tasks which runs function of emptying the tasks array and sends user back to home. REFRESH!
    $app->post("/delete_tasks", function() use ($app) {
      Task::deleteAll();
      return $app['twig']->render('delete_tasks.html.twig');
    });
    return $app;
?>
