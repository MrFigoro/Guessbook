<?php

class Model
{

	public $data;
	public $number;
	public $pagesCount;
	public $error = '';

	public function runny()
	{
		$this->data = array_reverse(file('book.txt')); // читает данные с конца файла

		$this->number = empty($_GET['number']) ? 1 : intval($_GET['number']);
		$this->calculatePagesCount();

		if (!$this->checkNumber()) {
			$this->error = 'Страница не существует';
		}

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

	public function checkForm() {
		if (isset($_POST['submit'])) {
			$person = $_POST["person"];
			$person = htmlspecialchars(strip_tags($person)); //ввод имени без тегов
			$person = str_replace("\r\n", "\t", $person); //замена символов переноса табуляцией

			$review = $_POST["review"];
			$review = htmlspecialchars(strip_tags($review)); //ввод отзыва без тегов
			$review = str_replace("\r\n", "\t", $review); //замена символов переноса табуляцией

			if (!empty($person) && !empty($review)) {
				$day = date("Y-m-d H:i:s", strtotime ("+0 hour"));
				// Записывается дата и время
				// В дальнейшем необходимо учитывать часовой пояс, в котором создается отзыв
				$text = "\n" . $day;
				$text .= "\t" . $person . ": " . "\t";
				$text .= $review; // склейка записи в книгу
				$file = fopen("book.txt", "a+");
				fwrite($file, $text);
				fclose($file);
			}
			//необходимо организовать оповещение о неверном вводе(в будующем)


//			return header("Location: $_SERVER[HTTP_REFERER]");
//			return $_POST['update'];
			echo "<meta http-equiv='refresh' content='0'>"; //автообновление страницы после отправки формы

		}
	}
}