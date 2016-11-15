jush.tr.simpledb = { sqlite_apo: /'/, sqlite_quo: /"/, bac: /`/ };

jush.urls.simpledb = ['http://docs.aws.amazon.com/AmazonSimpleDB/latest/DeveloperGuide/$key.html',
	'QuotingRulesSelect', 'CountingDataSelect', 'SortingDataSelect', 'SimpleQueriesSelect', 'UsingSelectOperators', 'RangeValueQueriesSelect', ''
];

jush.links2.simpledb = /(\b)(select|limit|(count)|(order\s+by|asc|desc)|(where)|(between|like|is|in)|(every)|(or|and|not|from|null|intersection))(\b)/gi;
