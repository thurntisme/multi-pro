<?php

$website = [
    [
        'name' => "WordPress Developer Resources",
        "link" => "https://developer.wordpress.org",
        "tags" => ["WordPress", "Development"],
        "description" => "Official WordPress developer documentation, including the REST API, themes, and plugins."
    ],
    [
        'name' => "WPBeginner",
        "link" => "https://wpbeginner.com",
        "tags" => ["WordPress", "Tutorials"],
        "description" => "Beginner-friendly WordPress tutorials, tips, and tricks."
    ],
    [
        'name' => "WP Tavern",
        "link" => "https://wptavern.com",
        "tags" => ["WordPress", "News"],
        "description" => "News and insights on WordPress, plugins, and the community."
    ],
    [
        'name' => "PHP.net",
        "link" => "https://www.php.net",
        "tags" => ["PHP", "Documentation"],
        "description" => "Official PHP documentation, with function references and guides."
    ],
    [
        'name' => "PHP: The Right Way",
        "link" => "https://phptherightway.com",
        "tags" => ["PHP", "Best Practices"],
        "description" => "An easy-to-read, community-driven guide to PHP best practices."
    ],
    [
        'name' => "Laracasts",
        "link" => "https://laracasts.com",
        "tags" => ["PHP", "Laravel", "Courses"],
        "description" => "In-depth PHP and Laravel video tutorials and courses."
    ],
    [
        'name' => "Dev.to PHP Community",
        "link" => "https://dev.to/t/php",
        "tags" => ["PHP", "Community"],
        "description" => "A community-driven collection of PHP tutorials and articles."
    ],
    [
        'name' => "ReactJS Official Documentation",
        "link" => "https://reactjs.org",
        "tags" => ["ReactJS", "Documentation"],
        "description" => "Comprehensive React documentation and guides for developers of all levels."
    ],
    [
        'name' => "Kent C. Dodds blog",
        "link" => "https://kentcdodds.com/blog",
        "tags" => ["ReactJS", "Advanced"],
        "description" => "Advanced React articles and insights from Kent C. Dodds."
    ],
    [
        'name' => "React Patterns",
        "link" => "https://reactpatterns.com",
        "tags" => ["ReactJS", "Patterns"],
        "description" => "Common design patterns and best practices for React development."
    ],
    [
        'name' => "Frontend Masters",
        "link" => "https://frontendmasters.com",
        "tags" => ["ReactJS", "Frontend", "Courses"],
        "description" => "High-quality courses for React and frontend development."
    ],
    [
        'name' => "Egghead.io",
        "link" => "https://egghead.io",
        "tags" => ["ReactJS", "Videos"],
        "description" => "Short and practical video tutorials on React and modern web development."
    ],
    [
        'name' => "CSS-Tricks",
        "link" => "https://css-tricks.com",
        "tags" => ["WordPress", "CSS", "Frontend"],
        "description" => "Frontend tips and tricks, including CSS, WordPress, and modern web development."
    ],
    [
        'name' => "Smashing Magazine",
        "link" => "https://smashingmagazine.com",
        "tags" => ["Frontend", "Design", "Development"],
        "description" => "Articles and resources for designers and developers, covering WordPress, ReactJS, and more."
    ],
    [
        'name' => "Stack Overflow",
        "link" => "https://stackoverflow.com",
        "tags" => ["WordPress", "PHP", "ReactJS", "Community"],
        "description" => "A popular Q&A site for solving coding challenges and sharing knowledge."
    ],
    [
        'name' => "github Awesome PHP",
        "link" => "https://github.com/ziadoz/awesome-php",
        "tags" => ["PHP", "Resources"],
        "description" => "A curated list of awesome PHP libraries, frameworks, and resources."
    ],
    [
        'name' => "github Awesome React",
        "link" => "https://github.com/enaqx/awesome-react",
        "tags" => ["ReactJS", "Resources"],
        "description" => "A curated list of awesome React resources, libraries, and projects."
    ],
    [
        'name' => "Reactiflux Discord Community",
        "link" => "https://www.reactiflux.com",
        "tags" => ["ReactJS", "Community"],
        "description" => "A helpful and active community of React developers on Discord."
    ],
    [
        'name' => "SitePoint",
        "link" => "https://www.sitepoint.com",
        "tags" => ["WordPress", "PHP", "ReactJS", "Web Development"],
        "description" => "Web development tutorials, including WordPress, PHP, and ReactJS."
    ]
];
define("DEFAULT_NETWORK_WEBSITES", $website);

$leads = [
    [
        'name' => 'Dan Abramov',
        'description' => 'Co-creator of Redux and a core contributor to React.',
        'tags' => ['React', 'Redux', 'JavaScript'],
        'social_links' => [
            'twitter' => 'https://twitter.com/dan_abramov',
            'github' => 'https://github.com/gaearon',
            'links' => 'https://overreacted.io/',
            'youtube' => null,
            'facebook' => null
        ]
    ],
    [
        'name' => 'Kent C. Dodds',
        'description' => 'Expert in JavaScript, testing, and creator of Testing Library.',
        'tags' => ['React', 'JavaScript', 'Testing'],
        'social_links' => [
            'twitter' => 'https://twitter.com/kentcdodds',
            'github' => 'https://github.com/kentcdodds',
            'links' => 'https://kentcdodds.com/',
            'youtube' => 'https://www.youtube.com/c/KentCDodds-vids',
            'facebook' => 'https://www.facebook.com/kentcdodds'
        ]
    ],
    [
        'name' => 'Wes Bos',
        'description' => 'Educator with popular courses on React, JavaScript, and CSS.',
        'tags' => ['React', 'JavaScript', 'CSS'],
        'social_links' => [
            'twitter' => 'https://twitter.com/wesbos',
            'github' => 'https://github.com/wesbos',
            'links' => 'https://wesbos.com/',
            'youtube' => 'https://www.youtube.com/c/WesBos',
            'facebook' => null
        ]
    ],
    [
        'name' => 'Sarah Drasner',
        'description' => 'Renowned for her work on SVG animations and web development tools.',
        'tags' => ['JavaScript', 'SVG', 'Web Animations'],
        'social_links' => [
            'twitter' => 'https://twitter.com/sarah_edo',
            'github' => 'https://github.com/sdras',
            'links' => 'https://sarahdrasnerdesign.com/',
            'youtube' => 'https://www.youtube.com/channel/UCB9_MlT4o8VCKuKpOdhtbvw',
            'facebook' => null
        ]
    ],
    [
        'name' => 'Addy Osmani',
        'description' => 'Google Chrome engineer focusing on performance and tooling.',
        'tags' => ['Performance', 'JavaScript', 'Tooling'],
        'social_links' => [
            'twitter' => 'https://twitter.com/addyosmani',
            'github' => 'https://github.com/addyosmani',
            'links' => 'https://addyosmani.com/',
            'youtube' => null,
            'facebook' => null
        ]
    ],
    [
        'name' => 'Cassidy Williams',
        'description' => 'Developer advocate known for engaging and humorous content.',
        'tags' => ['Developer Advocacy', 'React', 'JavaScript'],
        'social_links' => [
            'twitter' => 'https://twitter.com/cassidoo',
            'github' => 'https://github.com/cassidoo',
            'links' => 'https://cassidoo.co/',
            'youtube' => null,
            'facebook' => null
        ]
    ],
    [
        'name' => 'Evan You',
        'description' => 'Creator of Vue.js, a popular front-end JavaScript framework.',
        'tags' => ['Vue', 'JavaScript', 'Open Source'],
        'social_links' => [
            'twitter' => 'https://twitter.com/youyuxi',
            'github' => 'https://github.com/yyx990803',
            'links' => 'https://vuejs.org/',
            'youtube' => 'https://www.youtube.com/c/vuejs',
            'facebook' => null
        ]
    ],
    [
        'name' => 'Guillermo Rauch',
        'description' => 'CEO of Vercel and creator of Next.js.',
        'tags' => ['Next.js', 'JavaScript', 'Serverless'],
        'social_links' => [
            'twitter' => 'https://twitter.com/rauchg',
            'github' => 'https://github.com/rauchg',
            'links' => 'https://vercel.com/',
            'youtube' => null,
            'facebook' => null
        ]
    ],
    [
        'name' => 'Kyle Simpson',
        'description' => 'Author of "You Don’t Know JS" series, focused on JavaScript education.',
        'tags' => ['JavaScript', 'Education', 'Open Source'],
        'social_links' => [
            'twitter' => 'https://twitter.com/getify',
            'github' => 'https://github.com/getify',
            'links' => 'https://getify.me/',
            'youtube' => 'https://www.youtube.com/user/getify',
            'facebook' => null
        ]
    ],
    [
        'name' => 'TJ Holowaychuk',
        'description' => 'Creator of widely used Node.js libraries and Apex.sh.',
        'tags' => ['Node.js', 'Open Source', 'Tooling'],
        'social_links' => [
            'twitter' => 'https://twitter.com/tjholowaychuk',
            'github' => 'https://github.com/tj',
            'links' => 'https://apex.sh/',
            'youtube' => null,
            'facebook' => null
        ]
    ],
    [
        'name' => 'Maximilian Schwarzmüller',
        'description' => 'Creator of Academind, an online learning platform for web development.',
        'tags' => ['React', 'Vue', 'Node.js', 'Education'],
        'social_links' => [
            'Twitter' => 'https://twitter.com/maxedapps',
            'GitHub' => 'https://github.com/mschwarzmueller',
            'Website' => 'https://academind.com/',
            'YouTube' => 'https://www.youtube.com/c/Academind',
            'Facebook' => null
        ]
    ],
    [
        'name' => 'Brad Traversy',
        'description' => 'Full-stack developer and educator known for Traversy Media.',
        'tags' => ['JavaScript', 'React', 'Node.js', 'Web Development'],
        'social_links' => [
            'Twitter' => 'https://twitter.com/traversymedia',
            'GitHub' => 'https://github.com/bradtraversy',
            'Website' => 'https://traversymedia.com/',
            'YouTube' => 'https://www.youtube.com/c/TraversyMedia',
            'Facebook' => 'https://www.facebook.com/traversymedia'
        ]
    ],
    [
        'name' => 'Colt Steele',
        'description' => 'Web developer and educator offering in-depth programming courses.',
        'tags' => ['JavaScript', 'React', 'Python', 'Education'],
        'social_links' => [
            'Twitter' => 'https://twitter.com/ColtSteele',
            'GitHub' => null,
            'Website' => null,
            'YouTube' => 'https://www.youtube.com/c/ColtSteeleCode',
            'Facebook' => null
        ]
    ],
    [
        'name' => 'Florin Pop',
        'description' => 'Web developer and content creator sharing tips and challenges.',
        'tags' => ['Web Development', 'React', 'JavaScript', 'CSS'],
        'social_links' => [
            'Twitter' => 'https://twitter.com/florinpop1705',
            'GitHub' => 'https://github.com/florinpop17',
            'Website' => 'https://florin-pop.com/',
            'YouTube' => 'https://www.youtube.com/c/FlorinPop',
            'Facebook' => null
        ]
    ],
    [
        'name' => 'Quincy Larson',
        'description' => 'Founder of freeCodeCamp, a free coding education platform.',
        'tags' => ['JavaScript', 'Node.js', 'Education', 'Open Source'],
        'social_links' => [
            'Twitter' => 'https://twitter.com/ossia',
            'GitHub' => 'https://github.com/quincylarson',
            'Website' => 'https://www.freecodecamp.org/',
            'YouTube' => 'https://www.youtube.com/freecodecamp',
            'Facebook' => 'https://www.facebook.com/freecodecamp'
        ]
    ],
    [
        'name' => 'The Net Ninja (Shaun Pelling)',
        'description' => 'Educator providing practical web development tutorials.',
        'tags' => ['React', 'JavaScript', 'Node.js', 'Web Development'],
        'social_links' => [
            'Twitter' => null,
            'GitHub' => 'https://github.com/iamshaunjp',
            'Website' => 'https://www.thenetninja.co.uk/',
            'YouTube' => 'https://www.youtube.com/c/TheNetNinja',
            'Facebook' => null
        ]
    ],
    [
        'name' => 'Fireship.io (Jeff Delaney)',
        'description' => 'Educator offering short, informative programming tutorials.',
        'tags' => ['JavaScript', 'Firebase', 'React', 'Angular', 'Education'],
        'social_links' => [
            'Twitter' => 'https://twitter.com/fireship_dev',
            'GitHub' => 'https://github.com/fireship-io',
            'Website' => 'https://fireship.io/',
            'YouTube' => 'https://www.youtube.com/c/Fireship',
            'Facebook' => null
        ]
    ],
    [
        'name' => 'Dev Ed',
        'description' => 'Creative educator focusing on web development and design.',
        'tags' => ['Web Development', 'CSS', 'React', 'Design'],
        'social_links' => [
            'Twitter' => 'https://twitter.com/deved94',
            'GitHub' => null,
            'Website' => null,
            'YouTube' => 'https://www.youtube.com/c/DevEd',
            'Facebook' => null
        ]
    ],
    [
        'name' => 'Stephen Grider',
        'description' => 'Full-stack engineer and author of React and Node.js courses.',
        'tags' => ['React', 'Node.js', 'TypeScript', 'Education'],
        'social_links' => [
            'Twitter' => null,
            'GitHub' => null,
            'Website' => null,
            'YouTube' => null,
            'Facebook' => null
        ]
    ],
    [
        'name' => 'Kevin Powell',
        'description' => 'CSS evangelist who teaches modern CSS techniques.',
        'tags' => ['CSS', 'Web Development', 'Education'],
        'social_links' => [
            'Twitter' => 'https://twitter.com/KevinJPowell',
            'GitHub' => 'https://github.com/kevin-powell',
            'Website' => 'https://www.kevinpowell.co/',
            'YouTube' => 'https://www.youtube.com/c/KevinPowell',
            'Facebook' => null
        ]
    ]
];

define("DEFAULT_NETWORK_LEADS", $leads);
