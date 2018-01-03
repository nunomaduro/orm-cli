<?php

namespace App\Commands;


use OpenResourceManager\Client\AliasAccount as AliasClient;

class AliasAccountShowCommand extends APICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alias:show {id? : The alias account ID (optional)}
                            {--u|username= : The username of the alias account.}
                            {--p|page= : The page of results to display}
                            ';

    /**
     * Hide this command
     *
     * @var bool
     */
    protected $hidden = false;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show an Alias Account by it\'s ID, or username. Displays a paginated list when those parameters are omitted, a page parameter is available.';

    /**
     * AddressShowCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        parent::handle();

        $id = $this->argument('id');
        $username = $this->option('username');
        $page = $this->option('page');

        $aliasClient = new AliasClient($this->orm);
        $response = null;

        if (empty($id)) {
            if (empty($username)) {
                if (!empty($page)) {
                    $aliasClient->setPage($page);
                }
                $response = $aliasClient->getList();
            } elseif (!empty($username)) {
                $response = $aliasClient->getFromUsername($username);
            }
        } else {
            $response = $aliasClient->get($id);
        }

        // Cache the current ORM object
        $this->cacheORM($aliasClient->getORM());
        $this->displayResponseBody($response);
    }
}
