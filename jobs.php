<?php

require_once "app/Models/Job.php";
require_once "app/Models/Project.php";

$job1 = new Job('PHP Developer','This is a new Job!');
$job1->months = 16;

$job2 = new Job('Front Developer','This is a new Job!');
$job2->months = 24;

$job3 = new Job('DevOps','This is a new Job!');
$job3->months = 24;

$jobs = [ $job1, $job2, $job3 ];

$project1 = new Project('Project 1', 'Description One');

$projects = [ $project1 ];

function printElement(Printable $element) {
    if(!$element->visible) return;

    echo '<li class="work-position">
                <h5>'.$element->getTitle().'</h5>
                <p>'.$element->getDescription().'</p>
                <p>'.$element->getDurationAsString().'</p>
                <strong>Achievements:</strong>
                <ul>
                    <li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>
                    <li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>
                    <li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>
                </ul>
              </li>';
}