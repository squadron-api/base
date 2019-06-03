<?php

namespace Squadron\Base\Console\Commands;

use Illuminate\Console\Command;

class SetVersion extends Command
{
	protected $signature = 'squadron:version:set';
	protected $description = 'Set version in .env (from git last commit)';

	/**
	 * Execute the console command.
	 */
	public function handle(): void
	{
		$commitMessage = trim(exec('git log --pretty="%s" -n1 HEAD'));

		if (preg_match('#Merge branch \'release/(.*?)\'#is', $commitMessage, $matches))
		{
			$version = $matches[1];

			if (!empty($version))
			{
				$envFilePath = base_path('/.env');
				$envFileData = file_get_contents($envFilePath);

				$envFileData = preg_replace(
					'#^APP_VERSION=(.*?)$#im',
					sprintf('APP_VERSION=%s', $version),
					$envFileData
				);

				file_put_contents($envFilePath, $envFileData);

				$this->info(sprintf('Set APP version: %s', $version));
			}
		}
	}
}
