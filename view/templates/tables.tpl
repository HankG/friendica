{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
Database Tables
===============

* [Home](help)

| Table | Description |
|-------|-------------|
{{foreach $tables as $table}}
| [{{$table.name nofilter}}](help/database/db_{{$table.name nofilter}}) | {{$table.comment nofilter}} |
{{/foreach}}
