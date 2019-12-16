<?php
/*
 * This is a class file used to manage and render the content of a single page
 * @file: cpage.class.php
 * @brief: A class file to manage the content of the page
 * @author: GroupJ 
 *
 */
require_once(__DIR__."/../site_config.php");
class cpage {
    
    private $title;
    private $content;

    // Richiamando new cpage('title') creo l'oggetto pagina e setto il "titolo pagina"
    public function __construct($title){
        $this->title = $title;
    }
    
    public function __destruct(){
        //Clean up!
    }
    
    public function render(){
        $hidden_menu = "";
        $user = new user();
        $ulp = PLATFORM_PATH . $user->get_base_url(); // usergroup link prefix

        if (!$user->is_logged()) {
            $login_button = '<button type="button" class="btn btn-primary btn-lg p-2 m-4"><a class="nav-link text-left text-white py-1 px-0"  data-toggle="modal" href="#myModal"><i class="fas fa-sign-out-alt mx-3"></i><i class="fa fa-caret-right d-none position-absolute"></i><span class="text-nowrap mx-2">Log in</span></a></button>';
            $image = '<div class="p-2"id="indexlogo">
                                        <img src="' . PLATFORM_PATH . '/media/logopoli2.png" alt="logopoli" style="width: 100%; object-fit: contain"/>
                                </div>';
            $content = '<div>' . $image . $login_button . '</div>';
            $div_open = '
            <style> body { padding-left: 0; } </style>
            <div class="min_container mx-auto text-center" id="content">';
            /*background-image: url(' . PLATFORM_PATH . '/media/logopoli2.png);  background-repeat: no-repeat; background-position: center; */
        } else {
            // Custom menu definition for each group
            switch ($_SESSION["usergroup"]) {
                case "parent":
                    $par = new sparent();
                    $children = $par->get_children_info();

                    if ($par->get_current_child() == -1) {
                        $note = $par->get_num_unseen_notes(-1);
                        $hidden_menu_notes_entry = "";
                        if($note> 0)
                            $hidden_menu_notes_entry = '       <li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./listNotes.php">
       																<i class="fas fa-bell mx-3"></i><span class="text-nowrap mx-2">Check Notes <i class="fas fa-exclamation-circle mx-2" style="color:firebrick">'.$note.'</i></span></a>';
                        else if($note == 0)
                            $hidden_menu_notes_entry = '       <li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./listNotes.php"><i class="fas fa-bell mx-3"></i><span class="text-nowrap mx-2">Check Notes</span></a>';
                        $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0"><i class="fas fa-bullseye mx-3"></i><span class="text-nowrap mx-2">Check Marks</span></a></li>';
                        $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0"><i class="fas fa-book mx-3"></i><span class="text-nowrap mx-2">Check Homeworks</span></a></li>';
                        $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0"><i class="fas fa-user mx-3"></i><span class="text-nowrap mx-2">Check Attendance</span></a></li>';
                        $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0"><i class="fas fa-download mx-3"></i><span class="text-nowrap mx-2">Download Materials</span></a></li>';
                        $hidden_menu .= $hidden_menu_notes_entry;
                        $hidden_menu .= '<li class="nav-item dropdown"><a class="dropdown-toggle nav-link text-left text-white py-1 px-0 position-relative" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-user-graduate mx-3"></i><span class="text-nowrap mx-2">Students</span><i class="fas fa-caret-down float-none float-lg-right fa-sm"></i></a>
			
				<div class="dropdown-menu border-0 animated fadeIn" role="menu">';
                    } else {
                        $note = $par->get_num_unseen_notes($par->get_current_child());
                        $hidden_menu_notes_entry = "";
                        if($note> 0)
                            $hidden_menu_notes_entry = '       <li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./listNotes.php"><i class="fas fa-bell mx-3"></i><span class="text-nowrap mx-2">Check Notes <i class="fas fa-exclamation-circle mx-2" style="color:firebrick">'.$note.'</i></span></a>';
                        else if($note == 0)
                            $hidden_menu_notes_entry = '       <li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./listNotes.php"><i class="fas fa-bell mx-3"></i><span class="text-nowrap mx-2">Check Notes</span></a>';

                        $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="' . $ulp . 'checkMarks.php"><i class="fas fa-bullseye mx-3"></i><span class="text-nowrap mx-2">Check Marks</span></a></li>';
                        $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./checkHomeworks.php"><i class="fas fa-book mx-3"></i><span class="text-nowrap mx-2">Check Homeworks</span></a></li>';
                        $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./checkAttendance.php"><i class="fas fa-user mx-3"></i><span class="text-nowrap mx-2">Check Attendance</span></a></li>';
                        $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./downloadMaterial.php"><i class="fas fa-download mx-3"></i><span class="text-nowrap mx-2">Download Materials</span></a></li>';
                        $hidden_menu .= $hidden_menu_notes_entry;
                        $hidden_menu .= '<li class="nav-item dropdown"><a class="dropdown-toggle nav-link text-left text-white py-1 px-0 position-relative" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-user-graduate mx-3"></i><span class="text-nowrap mx-2">Students</span><i class="fas fa-caret-down float-none float-lg-right fa-sm"></i></a>
			
				<div class="dropdown-menu border-0 animated fadeIn" role="menu">';
                    }
                    foreach ($children as $i => $child_info) {
                        if ($child_info['StudentID'] == $par->get_current_child()) {
                            $hidden_menu .= '
				<a class="dropdown-item text-white" role="presentation" href="./index.php?action=switchChild&childID=' . $child_info["StudentID"] . '">
					<span>' . $child_info["Name"] . " " . $child_info["Surname"] . '</span>
                    <i class="fas fa-check-circle float-right mr-3 mt-2"></i>

				</a>';
                        } else {
                            $hidden_menu .= '
				<a class="dropdown-item text-white" role="presentation" href="./index.php?action=switchChild&childID=' . $child_info["StudentID"] . '">
					<span>' . $child_info["Name"] . " " . $child_info["Surname"] . '</span>
				</a>';
                        }
                    }
                    $hidden_menu .= '</div>
		</li>';
                    break;
                case "teacher":
                    $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./addLecture.php"><i class="fas fa-book-open mx-3"></i><span class="text-nowrap mx-2">Add Lecture</span></a></li>';
                    $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./listLectures.php"><i class="fas fa-bookmark mx-3"></i><span class="text-nowrap mx-2">List Lectures</span></a></li>';
                    $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./addAssignment.php"><i class="fas fa-user-clock mx-3"></i><span class="text-nowrap mx-2">Add Assignment</span></a></li>';
                    $hidden_menu .= '		<li class="nav-item dropdown"><a class="dropdown-toggle nav-link text-left text-white py-1 px-0 position-relative" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-file-upload mx-3"></i><span class="text-nowrap mx-2">Support Material</span><i class="fas fa-caret-down float-none float-lg-right fa-sm"></i></a>
								<div class="dropdown-menu border-0 animated fadeIn" role="menu">
								<a class="dropdown-item text-white" role="presentation" href="./listMaterial.php"><span>List Material</span></a>
								<a class="dropdown-item text-white" role="presentation" href="./uploadMaterial.php"><span>Upload Material</span></a>';
                    #Student specific actions
                    $hidden_menu .= '		<li class="nav-item dropdown"><a class="dropdown-toggle nav-link text-left text-white py-1 px-0 position-relative" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-user-tie mx-3"></i><span class="text-nowrap mx-2">Student Actions</span><i class="fas fa-caret-down float-none float-lg-right fa-sm"></i></a>
								<div class="dropdown-menu border-0 animated fadeIn" role="menu">
								<a class="dropdown-item text-white" role="presentation" href="./insertGrades.php"><span>Assign Grades</span></a>
								<a class="dropdown-item text-white" role="presentation" href="./addAbsence.php"><span>Record Presence</span></a>
								<a class="dropdown-item text-white" role="presentation" href="./registerNote.php"><span>Register Note</span></a>';

                    break;
                case "officer":
                    $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./batchActivateAuthentication.php"><i class="fas fa-envelope mx-3"></i><span class="text-nowrap mx-2">Parent Activation</span></a></li>';
                    // Upload info menu
                    // Stundent enrollment, classcomposition and (unused for now) settings
                    $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./addCommunications.php"><i class="fas fa-mail-bulk mx-3"></i><span class="text-nowrap mx-2">Add Communication</span></a></li>';
                    $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./studentEnrollment.php"><i class="fas fa-graduation-cap mx-3"></i><span class="text-nowrap mx-2">Enroll Student</span></a></li>';
                    $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./classCompositionModification.php"><i class="fas fa-users mx-3"></i><span class="text-nowrap mx-2">Handle Classes</span></a></li>';
                    $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./setClassTimetable.php"><i class="fas fa-clock mx-3"></i><span class="text-nowrap mx-2">Set Timetable</span></a></li>';
                    $hidden_menu .= '		<li class="nav-item dropdown"><a class="dropdown-toggle nav-link text-left text-white py-1 px-0 position-relative" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-user-tie mx-3"></i><span class="text-nowrap mx-2">Parent Info</span><i class="fas fa-caret-down float-none float-lg-right fa-sm"></i></a>
								<div class="dropdown-menu border-0 animated fadeIn" role="menu">
								<a class="dropdown-item text-white" role="presentation" href="./uploadParentCredentials.php"><span>Manual Insert</span></a>
								<a class="dropdown-item text-white" role="presentation" href="./uploadCSVParentCredentials.php"><span>CSV Upload</span></a>';
                    break;
                case "admin":
                    $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./registerAccount.php"><i class="fas fa-user-plus mx-3"></i><span class="text-nowrap mx-2">Register Account</span></a></li>';

                    break;
            }

            $logout_button = ' <li class="nav-item"><a class="nav-link text-left text-white py-1 px-0"  href="' . PLATFORM_PATH . '/logout.php"><i class="fas fa-sign-out-alt mx-3"></i><i class="fa fa-caret-right d-none position-absolute"></i><span class="text-nowrap mx-2">Log out</span></a></li>';
            $sidebar = '<ul class="nav flex-column shadow d-flex sidebar mobile-hid" id="sidebar">
                            <li class="nav-item logo-holder">
                                <a class="text-white float-left m-3" id="sidebarToggleHolder" href="#">
                                        <i class="fas fa-bars" id="sidebarToggle"></i>
                                </a>
                                <div class="text-center text-white logo p-4">
                                    <a class="text-white text-decoration-none p-2" id="title" href="#">
                                        <img id="sidebarlogo" src="' . PLATFORM_PATH . '/media/logopoli2.png" alt="logopoli" style="width: 100%; object-fit: contain"/>
                                    </a>
                                </div>
                            </li>
                            ' .
                            '<li class="nav-item"><a class="nav-link active text-left text-white py-1 px-0" href="./index.php"><i class="fas fa-home mx-3"></i><span class="text-nowrap mx-2">Home</span></a></li>' .
                            $hidden_menu .
                            $logout_button .
                        '</ul> ';

            $content = $sidebar;
            $div_open = "<div class='container min_container mb-5' id='content'>";
        }

        //La funzione render crea il contenuto della pagina in html
        echo $div_open;
        $mtitle = "<div class='text-center mb-5 mt-5'><h2>{$this->title}</h2></div>";
        echo $mtitle;
        echo $content;
        echo $this->content;
        echo "</div>";
        echo "</div>";//<div id=main>
    }
    
    public function setContent($content){
        $this->content = $content;
    }
}
?>