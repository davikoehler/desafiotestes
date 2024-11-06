<?php

return [
    [
        'campo', 
        'my cookie value', 
        '2023-01-31 00:00:00', 
        '3 hours + 50 minutes',
        'Set-Cookie: campo=my+cookie+value; Expires=Tue, 31 Jan 2023 03:50:00 GMT'
    ],
    [
        'campo', 
        'my cookie value', 
        '2023-01-31 00:00:00',
        '1 hours + 30 minutes',
        'Set-Cookie: campo=my+cookie+value; Expires=Tue, 31 Jan 2023 01:30:00 GMT'
    ],
    [
        'outrocampo', 
        'my cookie value com mais espaço', 
        '2023-02-06 13:00:00',
        '2 hours + 15 minutes',
        'Set-Cookie: outrocampo=my+cookie+value+com+mais+espa%C3%A7o; Expires=Mon, 06 Feb 2023 15:15:00 GMT'
    ]
];