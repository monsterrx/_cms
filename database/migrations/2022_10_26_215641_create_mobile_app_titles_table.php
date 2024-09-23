<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMobileAppTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_app_titles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->text('chart_title')->comment('Default is: Countdown Top 7');
            $table->text('chart_sub_title')->comment('Default is: Catch the countdown every Friday, 7 to 8pm with Hazel Hottie');
            $table->text('article_title')->comment("Default is: What\'s Hot?");
            $table->text('article_sub_title')->comment('Default is: News, Blogs, and Articles');
            $table->text('podcast_title')->comment('Default is: The Morning Rush');
            $table->text('podcast_sub_title')->comment('Default is: Mondays to Fridays from 6 to 10am');
            $table->text('articles_main_page_title')->comment('Default is: What\'s Hot?');
            $table->text('articles_main_page_subtitle')->comment('Default is: News, Blogs, and Articles');
            $table->text('podcast_main_page_title')->comment('Default is: Monster RX93.1 Podcast Channel');
            $table->text('youtube_main_page_title')->comment('Default is: Monster RX93.1 YouTube Channel');
            $table->string('location')->default('mnl');
            $table->timestamps();
        });

        /*DB::table('mobile_app_titles')->insert([
            'asset_id' => 1,
            'chart_title' => 'Countdown Top 7',
            'chart_sub_title' => 'Catch the countdown every Friday, 7 to 8pm with Hazel Hottie',
            'article_title' => "What's Hot?",
            'article_sub_title' => 'News, Blogs, and Articles',
            'podcast_title' => 'The Morning Rush',
            'podcast_sub_title' => 'Mondays to Fridays from 6 to 10am',
            'articles_main_page_title' => 'Monster News, Blogs and Articles',
            'articles_main_page_subtitle' => 'News, Blogs, and Articles',
            'podcast_main_page_title' => 'Monster RX93.1 Podcast Channel',
            'youtube_main_page_title' => 'Monster RX93.1 YouTube Channel',
            'location' => 'mnl'
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_app_titles');
    }
}
