<?php

require_once('TextRazor.php');

TextRazorSettings::setApiKey("YOUR_API_KEY_HERE");

function testAccount() {
    $accountManager = new AccountManager();
    print_r($accountManager->getAccount());
}

function testAnalysis() {
    $textrazor = new TextRazor();

    $textrazor->addExtractor('entities');
    $textrazor->addExtractor('words');
    $textrazor->addExtractor('spelling');

    $textrazor->addEnrichmentQuery("fbase:/location/location/geolocation>/location/geocode/latitude");
    $textrazor->addEnrichmentQuery("fbase:/location/location/geolocation>/location/geocode/longitude");

    $text = 'LONDON - Barclays misled shareholders and the public about one of the biggest investments in the banks history, a BBC Paorama investigation has found.';

    $response = $textrazor->analyze($text);
    if (isset($response['response']['entities'])) {
        foreach ($response['response']['entities'] as $entity) {
            print("Entity ID: " . $entity['entityId']);
		    $entity_data = $entity['data'];

            if (!is_null($entity_data)) {
			    print(PHP_EOL);
			    print("Entity Latitude: " . $entity_data["fbase:/location/location/geolocation>/location/geocode/latitude"][0]);
			    print(PHP_EOL);
			    print("Entity Longitude: " . $entity_data["fbase:/location/location/geolocation>/location/geocode/longitude"][0]);
		    }
		    print(PHP_EOL);
        }
    }
}

function testClassifier() {
    $textrazorClassifier = new ClassifierManager();

    $classifierId = 'test_cats_php';

    try {
        print_r($textrazorClassifier->deleteClassifier($classifierId));
    }
    catch (Exception $e){
        // Silently ignore missing classifier for now.
    }

    // Define some new categories and upload them as a new classifier.
    $newCategories = array();
    array_push($newCategories, array('categoryId' => '1', 'query' => "concept('banking')"));
    array_push($newCategories, array('categoryId' => '2', 'query' => "concept('health')"));

    $textrazorClassifier->createClassifier($classifierId, $newCategories);

    // Test the new classifier out with an analysis request.
    $textrazor = new TextRazor();
    $textrazor->addClassifier($classifierId);

    $text = 'Barclays misled shareholders and the public about one of the biggest investments in the banks history, a BBC Panorama investigation has found.';
    $response = $textrazor->analyze($text);

    print_r($response['response']);

    // The client offers various methods for manipulating your stored classifier.
    print_r($textrazorClassifier->allCategories($classifierId));

    print_r($textrazorClassifier->deleteClassifier($classifierId));
}

function testEntityDictionary() {
    $textrazorDictionary = new DictionaryManager();

    $dictionaryId = 'test_ents_php';

    try {
        print_r($textrazorDictionary->deleteDictionary($dictionaryId));
    }
    catch (Exception $e){
        // Silently ignore missing dictionary for now.
    }

    // Define a new dictionary, then add some test entries
    print_r($textrazorDictionary->createDictionary($dictionaryId, 'STEM', true, "eng"));

    $new_entities = array();
    array_push($new_entities, array("id" => "TV_1", "text" => "BBC Panorama"));

    print_r($textrazorDictionary->addEntries($dictionaryId, $new_entities));

    // To use the new dictionary, simply add its ID to your analysis request.

    $textrazor = new TextRazor();

    $textrazor->addEntityDictionary($dictionaryId);

    $text = 'Barclays misled shareholders and the public about one of the biggest investments in the banks history, a BBC Panorama investigation has found.';
    $response = $textrazor->analyze($text);

    // The matched entities will be available in the response

    print_r($response['response']['entities']);

    // The client offers various methods for manipulating your stored dictionary entries.

    print_r($textrazorDictionary->getEntry($dictionaryId, "TV_1"));

    print_r($textrazorDictionary->allEntries($dictionaryId, 10));

    print_r($textrazorDictionary->getDictionary($dictionaryId));

    print_r($textrazorDictionary->allDictionaries());

    print_r($textrazorDictionary->deleteDictionary($dictionaryId));

}

testAccount();
testAnalysis();
testEntityDictionary();
testClassifier();
