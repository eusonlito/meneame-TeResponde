<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tables extends Migration
{
    private static $tablesUp = [];
    private static $tablesDown = [];
    private static $exclude = '/^migrations$/';

    public function up()
    {
        $this->down();
        $this->upTables();
        $this->upIndexes();
    }

    private function upTables()
    {
        self::create('post', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->string('slug');
            $table->string('title')->index();
            $table->text('text');
            $table->string('link');
            $table->string('user');
            $table->smallInteger('karma');
            $table->datetime('created_at');
            $table->integer('remote_id');
        });

        self::create('comment', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->text('text');
            $table->string('link');
            $table->string('user');
            $table->smallInteger('karma');
            $table->smallInteger('number');
            $table->datetime('created_at');
            $table->integer('remote_id');

            $table->integer('post_id')->unsigned();
        });
    }

    public function down()
    {
        DB::statement('SET foreign_key_checks=0');

        self::drop('post');
        self::drop('comment');

        DB::statement('SET foreign_key_checks=1');
    }

    private function upIndexes()
    {
        Schema::table('comment', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('post')->delete('cascade');
        });
    }

    private static function create($name, $closure, $exclude = false)
    {
        if (preg_match(self::$exclude, $name)) {
            echo "\n".sprintf('Excluded Create %s', $name)."\n";
            return null;
        }

        self::$tablesUp[] = $name;

        if (($exclude === false) || !Schema::hasTable($name)) {
            Schema::create($name, $closure);
        }
    }

    private static function drop($name, $exclude = false)
    {
        if (preg_match(self::$exclude, $name)) {
            echo "\n".sprintf('Excluded Drop %s', $name)."\n";
            return null;
        }

        self::$tablesDown[] = $name;

        if ($exclude === false) {
            Schema::dropIfExists($name);
        }
    }
}
