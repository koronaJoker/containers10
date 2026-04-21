<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$tests = new TestFramework();

// test 1: check database connection
function testDbConnection() {
    global $config;

    resetDatabase();

    $db = new Database($config["db"]["path"]);

    return assertExpression($db !== null, "DB connected", "DB connection failed");
}

// test 2: test count method
function testDbCount() {
    global $config;

    resetDatabase();

    $db = new Database($config["db"]["path"]);
    $count = $db->Count("page");

    return assertExpression((int)$count === 3, "Count OK", "Count failed");
}

// test 3: test create method
function testDbCreate() {
    global $config;

    resetDatabase();

    $db = new Database($config["db"]["path"]);

    $id = $db->Create("page", [
        "title" => "Test Page",
        "content" => "Test Content"
    ]);

    return assertExpression($id > 0, "Create OK", "Create failed");
}

// test 4: test read method
function testDbRead() {
    global $config;

    resetDatabase();

    $db = new Database($config["db"]["path"]);

    $id = $db->Create("page", [
        "title" => "Read Test",
        "content" => "Read Content"
    ]);

    $data = $db->Read("page", $id);

    return assertExpression(
        $data["title"] === "Read Test",
        "Read OK",
        "Read failed"
    );
}

function testDbUpdate() {
    global $config;

    resetDatabase();

    $db = new Database($config["db"]["path"]);

    $id = $db->Create("page", [
        "title" => "Old Title",
        "content" => "Old Content"
    ]);

    $db->Update("page", $id, [
        "title" => "New Title"
    ]);

    $data = $db->Read("page", $id);

    return assertExpression(
        $data["title"] === "New Title",
        "Update OK",
        "Update failed"
    );
}

function testDbDelete() {
    global $config;

    resetDatabase();

    $db = new Database($config["db"]["path"]);

    $id = $db->Create("page", [
        "title" => "Delete Test",
        "content" => "Delete Content"
    ]);

    $db->Delete("page", $id);

    $data = $db->Read("page", $id);

    return assertExpression(
        $data === null || empty($data),
        "Delete OK",
        "Delete failed"
    );
}

function testPageRender() {
    $page = new Page(__DIR__ . '/../templates/index.tpl');

    resetDatabase();

    $output = $page->Render([
        "title" => "Hello",
        "content" => "World"
    ]);

    return assertExpression(
        strpos($output, "Hello") !== false &&
        strpos($output, "World") !== false,
        "Render OK",
        "Render failed"
    );
}

function resetDatabase() {
    global $config;

    $dbPath = $config["db"]["path"];
    $schemaPath = __DIR__ . '/../sql/schema.sql';

    // удалить файл базы
    if (file_exists($dbPath)) {
        unlink($dbPath);
    }

    // пересоздать через SQLite3
    $db = new SQLite3($dbPath);

    $sql = file_get_contents($schemaPath);
    $db->exec($sql);

    $db->close();
}

// add tests
$tests->add('Database connection', 'testDbConnection');
$tests->add('Count', 'testDbCount');
$tests->add('Create', 'testDbCreate');
$tests->add('Read', 'testDbRead');
$tests->add('Update', 'testDbUpdate');
$tests->add('Delete', 'testDbDelete');
$tests->add('Page render', 'testPageRender');

// run tests
$tests->run();

echo $tests->getResult();