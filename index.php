<?php
define('MESSAGE_PER_PAGE', 5);

class Controller
{
    private $model;
    private $view;

    public function run()
    {
        $this->model = new Model();
        $this->model->runny();
        if ($error = $this->model->getError())
        {
            var_dump($error);
        }
        else
        {
            $this->view = new View();
            $this->view->render($this->model->getData(), $this->model->getNumber(), $this->model->getPagesCount());
        }
    }
}


class Model
{
    private $data;
    private $number;
    private $pagesCount;
    private $error = '';

    public function runny()
    {
        $this->data = array_reverse(file('book.txt')); // читает данные с конца файла
	    //echo $data;

        $this->number = empty($_GET['number']) ? 1 : intval($_GET['number']);
        $this->calculatePagesCount();

        if (!$this->checkNumber())
            $this->error = 'Страница не существует';

        //$this->checkForm();
		if (isset($_POST['submit'])) {
			$this->checkForm();
		}
	}



    private function calculatePagesCount()
    {
        $this->pagesCount = ceil(count($this->data) / MESSAGE_PER_PAGE);
    }

    private function checkNumber()
    {
        return ($this->number > 0 && $this->number <= $this->pagesCount);
    }

    public function getData()
    {
        return array_slice($this->data, ($this->number - 1) * MESSAGE_PER_PAGE, MESSAGE_PER_PAGE);
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getPagesCount()
    {
        return $this->pagesCount;
    }

    public function getError()//: string
    {
        return $this->error;
    }

	/**
	 *
	 */
	public function checkForm() {
    	if (isset($_POST['submit'])) {
		    $person = $_POST["person"];
		    //$person = str_pad($person, 30, " ");
		    $person = htmlspecialchars(strip_tags($person)); //ввод имени без тегов
		    $person = str_replace("\r\n", "\t", $person); //замена символов переноса табуляцией
		    //$person = str_pad($person, 30, " "); //Добиваем имя пробелами до 30 символов
		    $review = $_POST["review"];
		    //$review = $review;
		    $review = htmlspecialchars(strip_tags($review));
		    $review = str_replace("\r\n", "\t", $review);
		    //$review = str_pad($review, 940, " "); //Добиваем отзыв пробелами до 940 символов
		    //echo $person.$review;
		    if (!empty($person) && !empty($review)) {
		    	$day = date("Y-m-d H:i:s", strtotime ("+1 hour"));
		    	// Просто добавляется час, потому что время сервера отличается на 1 час
			    //В дальнейшем необходимо учитывать часовой пояс, в котором создается отзыв
			    $text = "\n" . $day;
			    $text .= "\t" . $person . ": " . "\t";
			    $text .= $review; // склейка записи в книгу
			    $file = fopen("book.txt", "a+");
			    fwrite($file, $text);
			    fclose($file);

		    }
		    return header("Location: $_SERVER[HTTP_REFERER]");
	    }

    }

}

class View
{
    private $data;

    function render($data, $number, $pagesCount)
    {
        $this->data = $data;
        $this->viewHeader();
	    $this->viewForm();
	    $this->viewData();
	    $this->viewPagination($number, $pagesCount);
        $this->viewFooter();
    }

    function viewHeader()
    {
    	echo "<!DO"."C"."TYPE html>";
		echo '<html lang="ru">';
	    echo "<head>";
		echo "<title>Гостевая книга / И.М.Шабанович</title>";
	    echo "<meta charset=utf-8>";
		echo '<style>
			#page-layout{
				width: 650px;
				margin: 0 auto;
				position: relative;
			}
		</style>';
		echo "</head>";
		echo "<div id='page-layout'>";
	    echo '<p>Гостевая книга отзывов by Игорь Шабанович. Версия 2.<span style="font-style: italic"><td>(Ghost != Guest. It\'s joke!)</span></p>';
	    echo '<p>На странице выводится по '.MESSAGE_PER_PAGE.' отзыв(а, ов).</p><hr>';
	   // $td = date("Y-m-d H:i:s", strtotime ("+1 hour"));
	    //echo $td;
	    echo "</div>";
    }

    function viewData()
    {
        echo "<div id='page-layout'>";
        foreach ($this->data as $item) {
	        $items = str_replace("\t", "<br>", $item);
        	echo "<div><p>" . $items . "</p></div>";
        }
		//echo include ("book.txt");
        echo "</div>";
    }
	function viewForm() //функция выводит форму и обрабатывает вводимые данные
	{
		echo "<div id='page-layout'>";
			echo '<form method="POST" action="index.php">';
				echo '<p>Введите Ваше имя:</p>';
				echo '<div><input type="text" name="person" size="100%" maxlength="30"  value="" required placeholder="Гость" style="width: 90%"></div>';
				//Поле текста ожидает ввод, без ввода форма не отправляется
				echo '<p>Напишите, пожалуйста, какой-нибудь отзыв:</p>';
				/*echo '	<div class="input-width">
							<div class="width-setter">
								<textarea name="review" maxlength="940" rows="5" <!--cols="100"--> required placeholder="Напишите отзыв здесь."></textarea><br>
							<div>
						</div>';*/
				echo '<div><textarea name="review" maxlength="940" rows="5" cols="" required placeholder="Напишите отзыв здесь." style="width: 90%"></textarea></div>';
				//Текстовая площадка такжке ожидает ввод, если нет в поле ввода, то форма не отправляется
				echo '<input type="submit" name="submit" value="Отправить">';
				echo '<input type="reset" value="Отменить">';
			echo '</form><hr>';
		echo '</div>';

	}
    function viewPagination($number, $pagesCount)
    {
        echo "<div id='page-layout'>";
        for ($i = 1; $i <= $pagesCount; $i++) {
            if ($i != $number) {
                echo "<a href=\"index.php?number=$i\">$i</a> ";
            } else {
                echo $i . ' ';
            }
        }
        echo '<hr>';
        echo '</div>';
    }

    function viewFooter()
    {

    }
}
$controller = new Controller();
$controller->run();