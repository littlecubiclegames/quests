[![Build Status](https://travis-ci.org/littlecubiclegames/quests.svg?branch=master)](https://travis-ci.org/littlecubiclegames/quests)
# Quest System

## Installation
`composer require littlecubiclegames/quests`

## Usage
If you are not using Silex have a look at the `ServiceProvider` to see how to initialize all the services and to see which event listeners need to be registered.
You will have to implement `LittleCubicleGames\Quests\Initialization\QuestBuilderInterface` which will create a Entity class out of the quest definition data. You might also want to overwrite the currently used `ArrayStorage` with you own persistent storage.

Inside your Silex application you can:
```php
$app = new \Silex\Application();
$app->register(new \LittleCubicleGames\Quests\ServiceProvider(), [
    'cubicle.quests.quests' => [], // load your quests (see quest definition below)
    'cubicle.quests.slots' => [], // if you use the `StaticSlotLoader` define your quest slots here (see slot definition below)
    'cubicle.quests.initializer.questbuilder' => null, // define your QuestBuilder
]);

// In your app during boot, fetch current user id and initialize the quests
$userId = null;
$app['cubicle.quests.initializer']->initialize($userId);

// In your controller advance quests as desired
$app[''cubicle.quests.advancer']->startQuest($questId, $userId);
```

## Quest Components

#### Task
Quests can have a list of tasks which are required to be completed before the quest can be finished (e.g. User needs to login 5x).
A quest can have multiple tasks. The boolean operators ``AND`` and ``OR`` can be used to combine tasks. These operators can also be nested (e.g. User needs to login 5x OR The Sun is shining).

#### Slot
With slots it is possible to limit the amount of quests open at the time. Each slot allows one open quest. The availability of quests can be limited with a start and end date which can be useful for limited events. Slots are tied to a registry. 

#### Progress Listener
Every quest that gets started automatically registers for all events necessary to track the progress. This happens whenever a quest gets started or during a request you can register individual quests that you load from you database storage via ``ProgressListener::registerQuest(QuestInterface $quest)``.
Quests which upon progress change get completed, automatically change state into the ``completed`` state.
You can use the ``QuestInitializer`` to manually register all quests.

#### Task Completion Guard
The ``IsCompletedListener`` is a guard to make sure the quest can only change state to the ``completed`` state if the task are all finished.

#### No Reward Listener
Some quests might not have any reward for completion. Such quests will automatically be advanced from the ``completed`` to the ``closed`` state.

#### Rewards
Quests can have multiple rewards. Every reward type needs to have a Collector which implements the ``CollectorInterface``. Multiple rewards for one quest can be combined with the ``MultipleRewards`` class.
Rewards itself must only contain a type. Everything else should be customized to the needs of the collector.

#### Log
The quest log allows logging of every state change. It can serve two purposes:
* it makes the debug process easier because we know exactly when things happened
* it can be shown to the user as quest activity log

## Quest States

#### Available
The initial state the quest reaches once it leaves the quest pool.

#### In Progress
The quest was started and the user is working on completing all tasks.

#### Completed
The tasks in this quests are completed

#### Finished
The tasks are completed and the reward, if any, was collected.
The user is done with the quest and it should not be displayed anymore.

#### Rejected
The user or the system decided to abort or reject the quest.
The user is done with the quest and it should not be displayed anymore.

## Slot definition data (using the static slot loader)
```json
[
	{
		"id": "some-id",
		"registry": "reference to the registry id",
		"start": "2017-01-01",
		"end": "2018-01-01"
	}
]
```
Note: start and end are optional.

## Quest definition data

```json
{
	"id": "some-id",
	"task": {
		"operator": "and",
		"children" : [
			{
				"id": "task-id-1",
				"type": "finish-quests",
				"operator": "less-than",
				"value": 10
			},
			{
				"operator": "or",
				"children": [
					{
						"id": "task-id-2",
						"type": "finish-quests",
						"operator": "less-than",
						"value": 5
					},
					{
						"id": "task-id-3",
						"type": "finish-quests",
						"operator": "equal-to",
						"value": 2
					}
				]
			}
		]
	},
	"rewards": [
		{
			"type": "reward-type"
		}	
	]
}
```

## Creating your own components

* [Create a task](./docs/CreateATask.md)
