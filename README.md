php-json
========

De/Serializace libovolných objektů pomocí maperů.



$encoder = Taco\JSON\Encode([]);
$a = new DateTime();

echo $encoder->encode($a);
