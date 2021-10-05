<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Nette\Utils\Json;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    public function testWillGetValidationExceptionWithBadSchema(): void
    {
        $response = $this->withHeaders(
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        )->postJson(
            '/v1/phonebook/entries',
            [
                'data' => [
                    'attributes' => [
                        'first_name' => 'Will',
                        'middle_names' => 'Edmund Henry',
                        'last_name' => 'Percey',
                        //'email_address' => 'willpercey@example.com', //missing required field
                        'landline_number' => '193986560',
                        'mobile_number' => '07455588888',
                        'created_at' => '2021-10-05T16:22:23.000000Z',
                        'updated_at' => '2021-10-05T16:22:23.000000Z'
                    ]
                ]
            ]
        );

        $error = collect(Json::decode($response->getContent())->errors)->first();

        $this->assertEquals('/data/attributes/email_address', $error->source->pointer);
        $response->assertStatus(400);
    }
}
