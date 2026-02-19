<?php

declare(strict_types=1);

namespace App\Controllers;

use FaltLeap\LeapController;

class HomeController extends LeapController
{
    private const TAGLINES = [
        // PHP vs the world
        "PHP grew up. Your prejudice didn't. FaltLeap proves it.",
        "While you were configuring Webpack, FaltLeap shipped.",
        "Node has 47 left-pad alternatives. FaltLeap has match expressions and zero packages.",
        "No transpiling. No bundling. No existential crisis. Just FaltLeap.",
        "Your Node app needs a package to check if a number is odd. FaltLeap needs common sense.",
        "Strict types, named arguments, fibers — FaltLeap runs on modern PHP, not your assumptions.",
        "Your Node app needs 900 packages to start. FaltLeap needs php index.php.",
        "JavaScript added more build steps. FaltLeap just added features.",
        "FaltLeap: built with the language everyone loves to hate and secretly deploys.",
        "No node_modules. No tsconfig. No babel. Just FaltLeap.",
        "They argue about JS frameworks on Twitter. FaltLeap powers your app while they type.",
        "The mass grave of JS frameworks called. FaltLeap sends its regards.",

        // Modern web stack roasts
        "Your build pipeline has more stages than a Saturn V rocket. FaltLeap has zero.",
        "They debug 12-layer abstractions to render a list. FaltLeap renders it in one line.",
        "Their app needs Docker, Kubernetes, and three YAML files. FaltLeap needs index.php.",
        "npm install: 1,200 packages. FaltLeap install: git clone. Done.",
        "Next.js has more config files than features. FaltLeap has more features than files.",
        "Tailwind needs a build step for class names. FaltLeap doesn't need a build step at all.",
        "Deploying used to mean uploading files. With FaltLeap, it still does. And it's glorious.",
        "Their microservices have more network calls than users. FaltLeap has one process and ships.",
        "A modern blog needs a CI/CD pipeline and a container orchestrator. FaltLeap needs a folder.",
        "They write 200-line Webpack configs to import a PNG. FaltLeap uses an img tag.",

        // AI and the state of things
        "You need AI to write code now because the stack got too stupid for humans. Not FaltLeap.",
        "AI doesn't hallucinate — it learned from your npm dependency tree. FaltLeap has none.",
        "ChatGPT can scaffold your entire app but can't explain your Webpack config. FaltLeap needs neither.",
        "You need AI to navigate your own codebase. FaltLeap fits in your head.",
        "AI-generated code on top of 300 dependencies is a haunted house. FaltLeap is a clean room.",
        "Copilot autocompletes your code. Nobody can autocomplete your node_modules. FaltLeap has no modules.",
        "We asked AI to simplify the modern web stack. It described FaltLeap.",
        "AI writes boilerplate because humans shouldn't have to. FaltLeap just doesn't have boilerplate.",
        "The robots will take our jobs, but FaltLeap won't need a package.json for it.",
        "LLMs have read every JS framework ever written. FaltLeap only took them a minute.",
        "You need an AI agent to upgrade dependencies without breaking prod. FaltLeap has no dependencies to break.",
        "AI generates a full-stack app in seconds. Debugging the toolchain takes a week. FaltLeap has no toolchain.",

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
