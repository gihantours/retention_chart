<?php

namespace Tests\Feature;

use App\Http\Controllers\InsightController;
use App\Models\MemberOnboarding;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class InsightTest extends TestCase
{
    use DatabaseMigrations;


    public function test_get_onboading_data_from_csv_file_for_invalid_path()
    {
        $this->expectException(\ErrorException::class);
        $obj = new InsightController();
        $obj->getOnbordingData('invalid_csv_path');

    }


    public function test_get_onboading_data_from_csv_file_for_valid_path()
    {
        $obj = new InsightController();
        $data = $obj->getOnbordingData(storage_path('app/public/export.csv'));
        $this->assertIsArray($data);
        $this->assertGreaterThanOrEqual(1, count($data));

        $this->assertArrayHasKey('user_id', $data[0]);
        $this->assertArrayHasKey('created_at', $data[0]);
        $this->assertArrayHasKey('onboarding_perentage', $data[0]);
        $this->assertArrayHasKey('count_applications', $data[0]);

    }

    public function test_empty_value_handle_csv()
    {
        $obj = new InsightController();
        $results = $obj->handleEmptyValues('');
        $this->assertEquals(0, $results);

        $results = $obj->handleEmptyValues(1);
        $this->assertEquals(1, $results);

    }

    public function test_not_empty_values_handle_csv()
    {
        $obj = new InsightController();
        $results = $obj->handleEmptyValues(1);
        $this->assertEquals(1, $results);

    }


    public function test_week_start_date()
    {
        $this->artisan('migrate');

        $obj = new InsightController();
        MemberOnboarding::insert([
            [
                'user_id' => 1,
                'created_at' => '2016-07-19',
                'onboarding_perentage' => 0,
                'count_applications' => 1,
                'count_accepted_applications' => 1
            ]
        ]);

        $results = $obj->getWeeklyRetentionData();
        $this->assertEquals('2016-07-18', $results[0]->week_start);

    }

    //1. Create account - 0%
    public function test_create_account_onboarding_step_1()
    {

        $this->artisan('migrate');

        MemberOnboarding::insert([
            [
                'user_id' => 1,
                'created_at' => '2016-07-19',
                'onboarding_perentage' => 0,
                'count_applications' => 1,
                'count_accepted_applications' => 1
            ]
        ]);

        $obj = new InsightController();
        $results = $obj->getWeeklyRetentionData();

        $this->assertEquals('1', $results[0]->step_1);
        $this->assertEquals('0', $results[0]->step_2);
        $this->assertEquals('0', $results[0]->step_3);
        $this->assertEquals('0', $results[0]->step_4);
        $this->assertEquals('0', $results[0]->step_5);
        $this->assertEquals('0', $results[0]->step_6);
        $this->assertEquals('0', $results[0]->step_7);
        $this->assertEquals('0', $results[0]->step_8);

    }


    //2. Activate account - 20%
    public function test_activate_account_onboarding_step_2()
    {

        $this->artisan('migrate');

        MemberOnboarding::insert([
            [
                'user_id' => 1,
                'created_at' => '2016-07-19',
                'onboarding_perentage' => 20,
                'count_applications' => 1,
                'count_accepted_applications' => 1
            ]
        ]);

        $obj = new InsightController();
        $results = $obj->getWeeklyRetentionData();

        $this->assertEquals('1', $results[0]->step_1);
        $this->assertEquals('1', $results[0]->step_2);
        $this->assertEquals('0', $results[0]->step_3);
        $this->assertEquals('0', $results[0]->step_4);
        $this->assertEquals('0', $results[0]->step_5);
        $this->assertEquals('0', $results[0]->step_6);
        $this->assertEquals('0', $results[0]->step_7);
        $this->assertEquals('0', $results[0]->step_8);

    }


    //3. Provide profile information - 40%
    public function test_provide_profile_information_step_3()
    {

        $this->artisan('migrate');

        MemberOnboarding::insert([
            [
                'user_id' => 1,
                'created_at' => '2016-07-19',
                'onboarding_perentage' => 40,
                'count_applications' => 1,
                'count_accepted_applications' => 1
            ]
        ]);

        $obj = new InsightController();
        $results = $obj->getWeeklyRetentionData();

        $this->assertEquals('1', $results[0]->step_1);
        $this->assertEquals('1', $results[0]->step_2);
        $this->assertEquals('1', $results[0]->step_3);
        $this->assertEquals('0', $results[0]->step_4);
        $this->assertEquals('0', $results[0]->step_5);
        $this->assertEquals('0', $results[0]->step_6);
        $this->assertEquals('0', $results[0]->step_7);
        $this->assertEquals('0', $results[0]->step_8);

    }

    //4. What jobs are you interested in? - 50%
    public function test_what_jobs_are_you_interested_in_step_4()
    {

        $this->artisan('migrate');

        MemberOnboarding::insert([
            [
                'user_id' => 1,
                'created_at' => '2016-07-19',
                'onboarding_perentage' => 50,
                'count_applications' => 1,
                'count_accepted_applications' => 1
            ]
        ]);

        $obj = new InsightController();
        $results = $obj->getWeeklyRetentionData();

        $this->assertEquals('1', $results[0]->step_1);
        $this->assertEquals('1', $results[0]->step_2);
        $this->assertEquals('1', $results[0]->step_3);
        $this->assertEquals('1', $results[0]->step_4);
        $this->assertEquals('0', $results[0]->step_5);
        $this->assertEquals('0', $results[0]->step_6);
        $this->assertEquals('0', $results[0]->step_7);
        $this->assertEquals('0', $results[0]->step_8);

    }

    //5. Do you have relevant experience in these jobs? - 70%
    public function test_do_you_have_relevant_experience_in_these_jobs_step_5()
    {

        $this->artisan('migrate');

        MemberOnboarding::insert([
            [
                'user_id' => 1,
                'created_at' => '2016-07-19',
                'onboarding_perentage' => 70,
                'count_applications' => 1,
                'count_accepted_applications' => 1
            ]
        ]);

        $obj = new InsightController();
        $results = $obj->getWeeklyRetentionData();

        $this->assertEquals('1', $results[0]->step_1);
        $this->assertEquals('1', $results[0]->step_2);
        $this->assertEquals('1', $results[0]->step_3);
        $this->assertEquals('1', $results[0]->step_4);
        $this->assertEquals('1', $results[0]->step_5);
        $this->assertEquals('0', $results[0]->step_6);
        $this->assertEquals('0', $results[0]->step_7);
        $this->assertEquals('0', $results[0]->step_8);

    }


    //6. Are you a freelancer? - 90%
    public function test_are_you_a_freelancer_step_6()
    {

        $this->artisan('migrate');

        MemberOnboarding::insert([
            [
                'user_id' => 1,
                'created_at' => '2016-07-19',
                'onboarding_perentage' => 90,
                'count_applications' => 1,
                'count_accepted_applications' => 1
            ]
        ]);

        $obj = new InsightController();
        $results = $obj->getWeeklyRetentionData();

        $this->assertEquals('1', $results[0]->step_1);
        $this->assertEquals('1', $results[0]->step_2);
        $this->assertEquals('1', $results[0]->step_3);
        $this->assertEquals('1', $results[0]->step_4);
        $this->assertEquals('1', $results[0]->step_5);
        $this->assertEquals('1', $results[0]->step_6);
        $this->assertEquals('0', $results[0]->step_7);
        $this->assertEquals('0', $results[0]->step_8);

    }


    //7. Waiting for approval - 99%
    public function test_waiting_for_approval_step_7()
    {

        $this->artisan('migrate');

        MemberOnboarding::insert([
            [
                'user_id' => 1,
                'created_at' => '2016-07-19',
                'onboarding_perentage' => 99,
                'count_applications' => 1,
                'count_accepted_applications' => 1
            ]
        ]);

        $obj = new InsightController();
        $results = $obj->getWeeklyRetentionData();

        $this->assertEquals('1', $results[0]->step_1);
        $this->assertEquals('1', $results[0]->step_2);
        $this->assertEquals('1', $results[0]->step_3);
        $this->assertEquals('1', $results[0]->step_4);
        $this->assertEquals('1', $results[0]->step_5);
        $this->assertEquals('1', $results[0]->step_6);
        $this->assertEquals('1', $results[0]->step_7);
        $this->assertEquals('0', $results[0]->step_8);

    }


    //8. Approval - 100%
    public function test_approval_onboarding_step_8()
    {

        $this->artisan('migrate');
        MemberOnboarding::insert([
            [
                'user_id' => 1,
                'created_at' => '2016-07-19',
                'onboarding_perentage' => 100,
                'count_applications' => 1,
                'count_accepted_applications' => 1
            ]
        ]);

        $obj = new InsightController();
        $results = $obj->getWeeklyRetentionData();

        $this->assertEquals('1', $results[0]->step_1);
        $this->assertEquals('1', $results[0]->step_2);
        $this->assertEquals('1', $results[0]->step_3);
        $this->assertEquals('1', $results[0]->step_4);
        $this->assertEquals('1', $results[0]->step_5);
        $this->assertEquals('1', $results[0]->step_6);
        $this->assertEquals('1', $results[0]->step_7);
        $this->assertEquals('1', $results[0]->step_8);

    }


    public function test_getting_weekly_retention_data()
    {
        $this->artisan('migrate');
        $response = $this->getJson('api/insight/weekly-retention-data');

        $response->assertStatus(200);
    }


    public function test_chart_display_page()
    {

        $response = $this->get('/');

        $response->assertStatus(200);
    }

}
