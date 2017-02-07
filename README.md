# Quest System

## Quest Components

#### Task
Quests can have a list of tasks which are required to be completed before the quest can be finished (e.g. User needs to login 5x).
A quest can have multiple tasks. The boolean operators ``AND`` and ``OR`` can be used to combine tasks. These operators can also be nested (e.g. User needs to login 5x OR The Sun is shining).

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
