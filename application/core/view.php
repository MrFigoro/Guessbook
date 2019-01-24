<?php

class View
{

	public $data;

	function render($data, $number, $pagesCount) //очередность отрисовки страницы
	{
		$this->data = $data;
		$this->viewHeader();
		$this->viewForm();
		$this->viewPagination($number, $pagesCount);
		$this->viewData();
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
		echo '<p>Гостевая книга отзывов.<span style="font-style: italic"><td>(Версия 3)</span> На странице выводится по '.MESSAGE_PER_PAGE.' отзыв(а, ов).</p><hr>';
//		echo '<p>На странице выводится по '.MESSAGE_PER_PAGE.' отзыв(а, ов).</p><hr>';
		echo "</div>";
	}

	function viewData()
	{
		echo "<div id='page-layout'>";
		foreach ($this->data as $item) {
			$items = str_replace("\t", "<br>", $item);
			echo "<div><p>" . $items . "</p></div>";
		}
		echo "<hr>";
		echo "</div>";
	}

	function viewForm() //функция выводит форму и обрабатывает вводимые данные
	{
		echo "<div id='page-layout'>";
		echo '<form method="POST" action="../index.php">';
		echo '<p>Введите Ваше имя:</p>';
		echo '<div><input type="text" name="person" size="100%" maxlength="30"  value="" required placeholder="Гость" style="width: 90%"></div>';
		//Поле текста ожидает ввод, без ввода форма не отправляется
		echo '<p>Напишите, пожалуйста, какой-нибудь отзыв:</p>';
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
		echo "<div id='page-layout'>";
		echo '<p>Игорь Шабанович.<span style="font-style: italic"><td>(Версия 3)</span></p>';
		echo '<p>2019</p><hr>';
		echo "</div>";
	}
}
