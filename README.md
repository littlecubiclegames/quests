# Quest System

## Quest Components

#### Task
Quests can have a list of tasks which are required to be completed before the quest can be finished (e.g. User needs to login 5x).
A quest can have multiple tasks. The boolean operators ``AND`` and ``OR`` can be used to combine tasks. These operators can also be nested (e.g. User needs to login 5x OR The Sun is shining).

#### Progress Listener
Every quest that gets started automatically registers for all events necessary to track the progress. This happens whenever a quest gets started or during a request you can register individual quests that you load from you database storage via ``ProgressListener::registerQuest(QuestInterface $quest)``.
Quests which upon progress change get completed, automatically change state into the ``completed`` state.
You can use the ``QuestInitializer`` to manually register all quests.

#### Task Completion Guard
The ``IsCompletedListener`` is a guard to make sure the quest can only change state to the ``completed`` state if the task are all finished.

#### No Reward Listener
Some quests might not have any reward for completion. Such quests will automatically be advanced from the ``completed`` to the ``closed`` state.

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
	}
}
```
