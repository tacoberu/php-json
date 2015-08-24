php-json
========

De/Serialization object from/to JSON string, similar as json_encode()/json_decode().


## Example use code

    $encoder = Taco\JSON\Encode([]);
    echo $encoder->encode([42, 3.15, 'Salut']);

    $map = [];
    $map['DateTime'] = new Taco\JSON\DateTimeFormat();
    $map['Foo.Boo.Person'] = new Taco\JSON\AdhocFormat(function(Taco\JSON\Encoder $encoder, $value) {
    	$data = (object) [
    			'name' => $value->name,
    			'surname' => $value->surname,
    			];
    	if (! empty($value->age)) {
    		$data->age = $value->age;
    	}
    	return $encoder->makeDefinition('Foo.Boo.Person', $data);
    });
    $encoder = Taco\JSON\Encode($map);
    $json = $encoder->encode([42, 3.15, 'Salut', new DateTime()]);


    $decoder = Taco\JSON\Decode([]);
    echo $decoder->decode([42, 3.15, 'Salut']);


    $map = [];
    $map['DateTime'] = new Taco\JSON\DateTimeFormat();
    $map['Foo.Boo.Person'] = new AdhocFormat(Null, function(Taco\JSON\Decoder $decoder, $literal) {
    	if (isset($literal->age)) {
    		return new Foo\Boo\Person($literal->name, $literal->surname, $literal->age);
    	}
    	return new Foo\Boo\Person($literal->name, $literal->surname);
    });
    $decoder = Taco\JSON\Decode($map);
    print_r($decoder->decode($json));
    // [42, 3.15, 'Salut', new DateTime()]


## Example JSON code

    [
        'cislo': 42,
        'real': 3.1415,
        'datum': {
            '#t':'Date',
            '#v': '2005-08-15T15:52:01+00:00'
        },
        'author': {
            '#t':'Person',
            '#v': {
               'name': 'John',
               'surname': 'Dee'
            }
        }
    ]

