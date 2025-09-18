@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-book me-2"></i>API Documentation</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <nav class="nav nav-pills flex-column">
                                <a class="nav-link active" href="#authentication">Authentication</a>
                                <a class="nav-link" href="#endpoints">Endpoints</a>
                                <a class="nav-link" href="#responses">Responses</a>
                                <a class="nav-link" href="#examples">Examples</a>
                                <a class="nav-link" href="#errors">Error Codes</a>
                            </nav>
                        </div>
                        <div class="col-lg-9">
                            <div id="authentication" class="mb-5">
                                <h4 class="fw-bold mb-3">Authentication</h4>
                                <p>All API requests must include your API token in the Authorization header:</p>
                                <div class="bg-dark text-light p-3 rounded">
                                    <code>Authorization: Bearer YOUR_API_TOKEN</code>
                                </div>
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    You can find your API token in your dashboard after purchasing a plan.
                                </div>
                            </div>

                            <div id="endpoints" class="mb-5">
                                <h4 class="fw-bold mb-3">Available Endpoints</h4>
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">POST /api/sample</h6>
                                    </div>
                                    <div class="card-body">
                                        <p>Sample endpoint to test your API integration.</p>
                                        <strong>Credits Required:</strong> Based on your plan (2-4 credits per call)
                                    </div>
                                </div>
                            </div>

                            <div id="responses" class="mb-5">
                                <h4 class="fw-bold mb-3">Response Format</h4>
                                <p>All responses are returned in JSON format:</p>
                                <div class="bg-dark text-light p-3 rounded">
                                    <pre><code>{
  "message": "API call successful",
  "remaining_credits": 996,
  "data": {
    "timestamp": "2024-01-01T12:00:00.000000Z",
    "user_id": 1,
    "credits_used": 4
  }
}</code></pre>
                                </div>
                            </div>

                            <div id="examples" class="mb-5">
                                <h4 class="fw-bold mb-3">Code Examples</h4>

                                <h6 class="fw-bold">cURL</h6>
                                <div class="bg-dark text-light p-3 rounded mb-3">
                                    <pre><code>curl -X POST \
  {{ url('/api/sample') }} \
  -H 'Authorization: Bearer YOUR_API_TOKEN' \
  -H 'Content-Type: application/json' \
  -d '{"test": "data"}'</code></pre>
                                </div>

                                <h6 class="fw-bold">JavaScript</h6>
                                <div class="bg-dark text-light p-3 rounded mb-3">
                                    <pre><code>fetch('{{ url('/api/sample') }}', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_API_TOKEN',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({test: 'data'})
})
.then(response => response.json())
.then(data => console.log(data));</code></pre>
                                </div>

                                <h6 class="fw-bold">Python</h6>
                                <div class="bg-dark text-light p-3 rounded">
                                    <pre><code>import requests

url = '{{ url('/api/sample') }}'
headers = {
    'Authorization': 'Bearer YOUR_API_TOKEN',
    'Content-Type': 'application/json'
}
data = {'test': 'data'}

response = requests.post(url, headers=headers, json=data)
print(response.json())</code></pre>
                                </div>
                            </div>

                            <div id="errors" class="mb-5">
                                <h4 class="fw-bold mb-3">Error Codes</h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Message</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><code>401</code></td>
                                                <td>API token required</td>
                                                <td>Missing Authorization header</td>
                                            </tr>
                                            <tr>
                                                <td><code>401</code></td>
                                                <td>Invalid API token</td>
                                                <td>Token not found or inactive</td>
                                            </tr>
                                            <tr>
                                                <td><code>402</code></td>
                                                <td>Insufficient credits</td>
                                                <td>Not enough credits for API call</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            target.scrollIntoView({ behavior: 'smooth' });

            // Update active state
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
@endsection
