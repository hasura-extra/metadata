<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'get-inconsistent', description: 'Get inconsistent Hasura metadata')]
final class GetInconsistentMetadata extends BaseCommand
{
    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Getting...');

        $data = $this->metadataManager->getInconsistentMetadata();

        if (true === $data['is_consistent']) {
            $this->io->success('Current metadata is consistent with database sources!');

            return self::SUCCESS;
        }

        $this->io->table(
            ['TYPE', 'NAME', 'REASON'],
            array_map(
                fn ($item) => [$item['type'], $item['name'], $item['reason']],
                $data['inconsistent_objects']
            )
        );

        return self::FAILURE;
    }
}
