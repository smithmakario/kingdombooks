<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (auth()->user()?->is_superadmin)
                        <div class="space-y-4">
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <h3 class="text-lg font-semibold text-gray-900">Launch Email Subscribers</h3>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="email-list-refresh" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-wider text-gray-700 shadow-sm hover:bg-gray-50">
                                        Refresh
                                    </button>
                                    <button type="button" id="email-list-copy" class="inline-flex items-center rounded-md border border-indigo-600 bg-indigo-600 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-white shadow-sm hover:bg-indigo-500">
                                        Copy All
                                    </button>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600" id="email-list-count">0 email(s) saved</p>
                            <ul id="email-list" class="max-h-72 overflow-y-auto rounded-md border border-gray-200 divide-y divide-gray-200"></ul>
                            <p class="text-xs text-gray-500">
                                Shows emails captured from the launch signup form on this browser/device.
                            </p>
                        </div>

                        <script>
                            (function() {
                                var key = 'kbk_launch_emails';
                                var listEl = document.getElementById('email-list');
                                var countEl = document.getElementById('email-list-count');
                                var refreshBtn = document.getElementById('email-list-refresh');
                                var copyBtn = document.getElementById('email-list-copy');

                                if (!listEl || !countEl || !refreshBtn || !copyBtn) return;

                                function readEmails() {
                                    try {
                                        var raw = localStorage.getItem(key);
                                        if (!raw) return [];
                                        var parsed = JSON.parse(raw);
                                        return Array.isArray(parsed) ? parsed : [];
                                    } catch (err) {
                                        return [];
                                    }
                                }

                                function render() {
                                    var emails = readEmails();
                                    countEl.textContent = emails.length + ' email(s) saved';
                                    listEl.innerHTML = '';

                                    if (!emails.length) {
                                        listEl.innerHTML = '<li class="px-3 py-3 text-sm italic text-gray-500">No launch subscriber emails yet.</li>';
                                        return;
                                    }

                                    emails.forEach(function(email) {
                                        var li = document.createElement('li');
                                        li.className = 'px-3 py-3 text-sm text-gray-800 break-all';
                                        li.textContent = email;
                                        listEl.appendChild(li);
                                    });
                                }

                                function copyEmails() {
                                    var emails = readEmails();
                                    if (!emails.length) {
                                        alert('No subscriber emails to copy yet.');
                                        return;
                                    }

                                    var text = emails.join('\n');
                                    if (navigator.clipboard && navigator.clipboard.writeText) {
                                        navigator.clipboard.writeText(text).then(function() {
                                            alert('Subscriber emails copied.');
                                        }).catch(function() {
                                            alert('Could not copy automatically. Please copy manually.');
                                        });
                                        return;
                                    }

                                    var area = document.createElement('textarea');
                                    area.value = text;
                                    document.body.appendChild(area);
                                    area.select();
                                    document.execCommand('copy');
                                    document.body.removeChild(area);
                                    alert('Subscriber emails copied.');
                                }

                                refreshBtn.addEventListener('click', render);
                                copyBtn.addEventListener('click', copyEmails);
                                render();
                            })();
                        </script>
                    @else
                        {{ __("You're logged in!") }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
