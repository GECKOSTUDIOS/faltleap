<?php

declare(strict_types=1);

namespace App\Controllers;

use FaltLeap\LeapController;

class HomeController extends LeapController
{
    private const TAGLINES = [
        // PHP vs the world
        "PHP grew up. Your prejudice didn't.",
        "While you were configuring Webpack, we shipped.",
        "PHP 8 has match expressions. Node has 47 left-pad alternatives.",
        "No transpiling. No bundling. No existential crisis about which runtime to use.",
        "PHP doesn't need a package to check if a number is odd.",
        "Strict types, named arguments, fibers — but sure, tell me again how PHP is dead.",
        "Your Node app needs 900 packages to start. Ours needs php index.php.",
        "PHP evolved. JavaScript just added more build steps.",
        "Built with the language everyone loves to hate and secretly deploys.",
        "No node_modules. No tsconfig. No babel. No drama.",
        "PHP: powering 80% of the web while you argue about frameworks on Twitter.",
        "The mass grave of JS frameworks called. PHP sends its regards.",

        // Modern web stack roasts
        "Your frontend build pipeline has more stages than a Saturn V rocket.",
        "Somewhere a developer is debugging why their 12-layer abstraction can't render a list.",
        "Your app needs Docker, Kubernetes, and three YAML files just to say Hello World.",
        "npm install: 1,200 packages. You wrote: 40 lines of code. Sleep well.",
        "The average Next.js project has more config files than features.",
        "Tailwind: because writing CSS was too easy and we needed a build step for class names.",
        "Remember when deploying meant uploading files via FTP? That guy retired happy.",
        "Your microservices architecture has more network calls than users.",
        "The modern web: where a blog needs a CI/CD pipeline and a container orchestrator.",
        "Somewhere a senior dev is writing a 200-line Webpack config to import a PNG.",

        // AI and the state of things
        "You need AI to write code now because the stack got too stupid for humans.",
        "AI doesn't hallucinate — it just learned from your npm dependency tree.",
        "ChatGPT can scaffold your entire app. It still can't explain your Webpack config.",
        "The fact that you need AI to navigate your own codebase says everything.",
        "AI-generated code is fine. AI-generated code on top of 300 dependencies is a haunted house.",
        "Copilot autocompletes your code. Nobody can autocomplete your node_modules.",
        "We asked AI to simplify the modern web stack. It wrote PHP.",
        "AI writes better boilerplate than humans because humans shouldn't be writing boilerplate.",
        "The robots will take our jobs, but at least they won't need a package.json to do it.",
        "LLMs have read every JS framework ever written. They're the real victims here.",
        "You need an AI agent just to upgrade your dependencies without breaking prod.",
        "AI can generate a full-stack app in seconds. Debugging the toolchain still takes a week.",

        // FaltLeap: too simple for AI to be necessary
        "20 files. You can read them all yourself. No AI required.",
        "Other frameworks need AI to explain them. Ours fits on a napkin.",
        "You don't need Copilot when your entire framework is shorter than its context window.",
        "AI is great for navigating complexity. FaltLeap just doesn't have any.",
        "They need AI to manage dependencies. We need AI to... nothing. We're good.",
        "Your coworker needs ChatGPT to understand their codebase. You just need 20 minutes.",
        "FaltLeap: the framework an intern can understand without asking a chatbot.",
        "No AI was needed to build this. But one was needed to build your Webpack config.",
        "When your framework is 3,200 lines, the AI in your head is enough.",
        "Everyone's using AI to write glue code. We just don't have glue code.",
        "LLMs are great at summarizing complexity. We skipped the complexity part.",
        "The best use of AI is not needing it. Welcome to FaltLeap.",

        // Go and Rust roasts
        "Go devs write if err != nil 47 times. FaltLeap devs ship features.",
        "Rust guarantees memory safety. FaltLeap guarantees you'll go home on time.",
        "They rewrote their PHP app in Go. It's 4ms faster and took 6 months longer.",
        "Rust developers mass-rewrite everything in Rust. FaltLeap developers just deploy.",
        "Go promised simplicity. Then added generics anyway. FaltLeap was simple from day one.",
        "Your Rust web app compiles in 12 minutes. FaltLeap refreshes in 12 milliseconds.",
        "Go was designed at Google by geniuses. FaltLeap was designed to not need one.",
        "Rust: fighting the borrow checker so your web form can submit. FaltLeap: just submit.",
        "Go needs a goroutine to feel special. FaltLeap needs a browser and a dream.",
        "They spent a year rewriting their CRUD app in Rust. FaltLeap had it done before lunch.",
        "Go error handling: 30% of your codebase. FaltLeap error handling: an exception.",
        "Rust devs mass-explain why lifetimes matter for a login page. FaltLeap devs are at the pub.",
    ];

    public function welcome()
    {
        $this->view->data = (object) [
            'tagline' => self::TAGLINES[array_rand(self::TAGLINES)],
        ];
        $this->view->single('welcome');
    }

    public function index()
    {
        $this->view->render('home/index');
    }
}
