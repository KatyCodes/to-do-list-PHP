<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/List.php";

//Creates empty array if it doesnt exist
    session_start();
    if (empty($_SESSION['list_of_tasks'])) {
      $_SESSION['list_of_tasks'] = array();
    }


    $app = new Silex\Application();
//creates home pages
    $app->get("/" , function() {

      $output = "";

      $all_tasks = Task:: getAll(); //variable equals function in list.php

      if (!empty($all_tasks)) {
        $output = $output . "
            <h1>To Do List</h1>
            <p>Here are all your tasks:</p>
        ";

        foreach ($all_tasks as $task) {
            $output .= "<p>" . $task->getDescription() . "</p>";
        }
      }
//form that will take new task and append to /tasks page
      $output .=  "
        <form action='/tasks' method='post'>
          <label for='description'>Task Description</label>
          <input id='description' name='description' type='text'>
          <button type='submit'>Add Task</button>
        </form>
      ";
//Button that deletes previously posted tasks
      $output .= "
          <form action='/delete_tasks' method='post'>
            <button type='submit'>delete</button>
          </form>
      ";
      return $output;
    });
//Appends user input from form into /tasks page
    $app->post("/tasks", function(){
      $task = new Task($_POST['description']);
      $task->save();
      return "
          <h1>You created a task!</h1>
          <p>" . $task->getDescription() . "</p>
          <p><a href='/'>View your list of things to do.</a></p>
      ";
    });
//Sends user to page /delete_tasks which runs function of emptying the tasks array and sends user back to home. REFRESH!
    $app->post("/delete_tasks", function() {
      Task::deleteAll();
      return "
      <h1>List Cleared!</h1>
      <p><a href='/'>Home</a></p>
      ";
    });
    return $app;
?>
