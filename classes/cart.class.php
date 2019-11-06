<?php
require("site_config.php");
class Cart{
	private $cart;
	function __construct($cart = array()) {
	    if (is_array($cart))
	    {
	         $this->cart = $cart;
	    }
	    else
	    {
	         // maybe print some error, informing the developer that he's using the cart class incorrectly
	         // or better yet, trigger a PHP warning:
	         trigger_error('Cart class constructor expects an array parameter', E_USER_WARNING);
	    }
	}
	private function connectMySQL() {
        $mysqli = new mysqli(DB_NAME, DB_USER, DB_PASSWORD, DB_NAME);
        /* check connection */
        if ($mysqli->connect_errno) {
            printf("Connect failed: %s\n", $mysqli->connect_errno);
            exit();
        }
        return $mysqli;
	}
	private function connectMongoDB() {
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        return $manager;
    }
/////////
	public function in_multiarray($elem, $array)
    {
    	if(empty($array)){
    		return false;
    	}
        $top = sizeof($array) - 1;
        $bottom = 0;
        while($bottom <= $top)
        {
            if($array[$bottom] == $elem){
                return true;
            }
            else {
                if(is_array($array[$bottom])){
                	/*$recursive[0] = $array['id'];
                    if($this->in_multiarray($elem, ($recursive))){
                    	print_r($array[$bottom]);
                        return true;*/
                    if(array_search($elem, $array[$bottom])){
                    	return true;
                    }
                }
            }
    
            $bottom++;
        }        
        return false;
    }
//////////////
	public function addItem($id, $quantity = 1) {
		/*if(!$this->checkNatural($id)){//controlla se solo numeri
			trigger_error('addItem function expects a natural number as parameter', E_USER_ERROR);
		}*/
		$quantity = (int) $quantity;
		if($this->in_multiarray($id, $this->cart)){
			//die('Prodotto già aggiunto al carello!');
			return false;
		}
		$this->cart[] = array('id' => $id, 'quantity' => $quantity);
		return true;
	}

	public function getTotal(){
		if (!empty($this->cart)) {
			$tot = 0;
			foreach ($this->cart as $arr) {
				//Ottengo le informazioni del prodotto e le inserisco in $item.
				$item = $this->getItemInfos($arr['id']);
				$tot = $tot +$item['price'];
			}
			return $tot;
		}
		else{
			return 0;
		}
	}

	public function showItems(){
		if (!empty($this->cart)) {
			$tot = 0;
			foreach ($this->cart as $arr) {
				//Ottengo le informazioni del prodotto e le inserisco in $item.
				$item = $this->getItemInfos($arr['id']);
				// Print the item:
				if(isset($item)){
					printf('<p><i class="fa fa-diamond" aria-hidden="true"></i> '.$arr['quantity'].' x '.$item['price'].' €. <a href="./cart.php?delete=1&id='.$arr['id'].'">Delete</a><p>');
					$tot = $tot +$item['price'];
				}
			}
			
			print ("<center>Total:<strong> {$tot}€</strong>");
			print '</center>';
			print '<center><br><br><input class ="button" type = "button" value = "Paga ora" onClick="location.href =\'./checkout.php\'">';
			print '<input type = "button" class="button"value = "Paga al ritiro" onClick="location.href =\'./checkout.php?action=pickAndPay\'">
				</center>';
		}
		else {
			print 'Your shopping cart is empty!';
		}
	}
	public function getItems(){
		$content = "";
		if (!empty($this->cart)) {
			$tot = 0;
			foreach ($this->cart as $arr) {
				//Ottengo le informazioni del prodotto e le inserisco in $item.
				$item = $this->getItemInfos($arr['id']);
				// Print the item:
				if(isset($item)){ 
					$content .= '<p><i class="fa fa-diamond" aria-hidden="true"></i> '.$arr['quantity'].' x '.$item['price'].' €. <a href="./cart.php?delete=1&id='.$arr['id'].'">Delete</a><p>';
					$tot = $tot +$item['price'];
				}
			}
			
			//print '</div>';
			$content .= "<center>Total:<strong> {$tot}€</strong>";
			$content .= '</center>';
			$content .= '<center><br><br><input class ="button" type = "button" value = "Paga ora" onClick="location.href =\'./checkout.php\'">';
			$content .= '<input type = "button" class="button"value = "Paga al ritiro" onClick="location.href =\'./checkout.php?action=pickAndPay\'"></center>';

			//Bottoni
		}
		else {
			$content .= 'Your shopping cart is empty!';
		}
		return $content;
	}
	public function getItemInfos($id){
		//TODO: UPDATE!
		$item = array();
		try {
			$mng = $this->connectMongoDB();
			//$query = new MongoDB\Driver\Query([]);  
			$_id = new MongoDB\BSON\ObjectID($id);
			$filter = [ '_id' => $_id];
    		$query = new MongoDB\Driver\Query($filter);     
	        $rows = $mng->executeQuery("DiamondDB.PolishedDiamond", $query);
    
			foreach ($rows as $row) {
				$item["cut_type"] = $row->cut_type;// Name of the item (what show on cart?)
				$item["cut_quality"] = $row->cut_quality;
				//$item["order_id"] = $row->order_id;
				$item["rdiamond_id"] = $row->rdiamond_id;
				$item["color"] = $row->color;
				$item["clarity"] = $row->clarity;
				$item["carats"] = $row->carats;
				$item["price"] = $row->price;
				$item["warehouse_position"] = $row->warehouse_position;
				$item["thumbprint"] = $row->thumbprint;
				$item["_id"] = $row->_id;
			}
			
		} catch (MongoDB\Driver\Exception\Exception $e) {

			$filename = basename(__FILE__);
			
			echo "The $filename script has experienced an error.\n"; 
			echo "It failed with the following exception:\n";
			
			echo "Exception:", $e->getMessage(), "\n";
			echo "In file:", $e->getFile(), "\n";
			echo "On line:", $e->getLine(), "\n";       
		}
		return $item;
    }

	public function isEmpty() {
		return (empty($this->cart));
	}

	public function countItems(){
		return count($this->cart);
	}

	public function getCart(){
		return ($this->cart);
	}

	public function checkNatural($str){
       //return preg_match('/[^0-9]+$/', $str) ? false : $str;
    	//return preg_match('/^(?:0|[1-9][0-9]*)$/', $str) ? false : $str;
    	if (preg_match('/^[0-9]+$/', $str)) {
    		return true;
    	}
    	else {
    		return false;
    	}
    }

    public function showCheckoutCart(){
    	if (!empty($this->cart)) {
    		$tot = 0;
    		$content = "<strong>Your order consists of the following items:</strong><br>";
			foreach ($this->cart as $arr) {
				//Ottengo le informazioni del prodotto e le inserisco in $item.
				$item = $this->getItemInfos($arr['id']);
				// Print the item:
				if(isset($item)){
				$content .= '<p><strong>Diamond</strong>:  '.$arr["quantity"].' x '.$item['price'].' €.<p>';
				$tot = $tot +$item['price'];
				}
			}
		$content .= "Total:<strong> {$tot}€</strong>";
		return $content;
    	}
    	else {
    		return false;
    	}
    }

    private function deleteFromArray($array, $index){
		// cancello l'elemento dell'array con l'indice passato alla funzione
		unset($array[$index]);
		// ritorno un'array ordinato
		return array_merge($array);
		print_r($array);
    }
    public function deleteItem($id, $quantity = 1) {
    	/** !!Bug fix, il primo articolo aggiunto non poteva essere eliminato, semplice bypass!*/
    	if($this->cart['0']['id'] == $id){
    		$this->cart = $this->deleteFromArray($this->cart, 0);
    		return true;
    	}
    	//Devo verificare manualmente se il primo articolo è quello da eliminare, ciò avviene probabilemnte perchè in questo caso in_multiarray restituisce la posizione dell'indice dove trova l'id, in questo caso 0.È proprio lo zero a causare questo problema
    	/**Fine Bugfix**/
		$quantity = (int) $quantity;
		if($index = $this->in_multiarray($id, $this->cart)){//Trovo qual è l'indice relativo al prodotto nel carrello
			$this->cart = $this->deleteFromArray($this->cart, $index);
			return true;
		}
		else{
			return false;
			//Prodotto non presente nel carello
		}
	}
}
?>
