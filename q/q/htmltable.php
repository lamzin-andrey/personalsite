<?php
class HtmlTable {
	private string $className = '';
	private bool $doNumRows = false;
	private string $h = '';
	/**
	 * @var array<int, string> $fields
	*/
	private array $fields = [];
	private array $colW = [];
	
	public function __construct(string $className = '', bool $doNumRows = false) {
		$this->className = $className;
		$this->doNumRows = $doNumRows;
	}
	
	/**
	 * For example table data ia array of StdClass {name:string, quantity:int}
	 * $t->setH('Product name', 'name', 'Quantity in warehouse', 'quantity');
	*/
	public function setH(string $columnHeading, string $dataObjectFieldName):void
	{
		$args = func_get_args();
		$sz = count($args);
		$this->h = '<tr>';
		if ($this->doNumRows) {
			$this->h .= '<th>&nbsp;</th>';
		}
		for ($i = 0; $i < $sz; $i += 2) {
			$this->h .= '<th>' . $args[$i] . '</th>';
			$this->fields[] = $args[$i + 1];
		}
		$this->h .= '</tr>';
	}
	
	/**
	 * @param array<int, StdClass>
	*/
	public function html(array $a):string
	{
		$sz = count($this->fields);
		$s = '';
		if ($this->className) {
			$s = ' class="' . $this->className . '"';
		}
		$s = "<table{$s}><thead>{$this->h}</thead><tbody>";
		foreach ($a as $i => $o) {
			$s .= '<tr>';
			if ($this->doNumRows) {
				$j = $i + 1;
				$s .= "<td class=\"headbg\">{$j}</td>";
			}
			for ($i = 0; $i < $sz; $i++) {
				$k = $this->fields[$i];
				$v = $o->$k;
				$w = $this->colW[$i] ?? '';
				if ($w) {
					$w = " width=\"{$w}\" ";
				}
				$s .= "<td{$w}>{$v}</td>";
			}
			$s .= '</tr>';
		}
		$s .= '</tbody></table>';
		
		return $s;
	}
	/**
	 * If table two cols $t->setColW('10%', '50%');
	 * If table three cols $t->setColW('10%', '45%', '45%');
	*/
	public function setColW():void
	{
		$args = func_get_args();
		$sz = count($args);
		foreach ($args as $w) {
			$this->colW[] = $w;
		}
	}
}
