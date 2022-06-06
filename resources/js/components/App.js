import {React, useState} from 'react';
import ReactDOM from 'react-dom';


function App() {
    const [emails, setEmails] = useState([]);

    function handleKeyDown(e) {
        if (e.key !== 'Enter') {
            return;
        }

        const value = e.target.value;
        if (! value.trim()) {
            return;
        }

        setEmails([...emails, value]);
        e.target.value = '';
    }

    function removeEmails(index) {
        setEmails(emails.filter((email, i) => i !== index));
    }

    function handleSubmit(e) {

    }

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <div className="card-header">
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb">
                                    <li className="breadcrumb-item"><a href="/home">Dashboard</a></li>
                                    <li className="breadcrumb-item active" aria-current="page">Invite friends</li>
                                </ol>
                            </nav>
                        </div>
                        <div className='card-body'>
                            <div className="row">
                                <div className="col-sm-6 offset-sm-2">
                                    <label>Email your invite</label>
                                </div>
                            </div>
                            <div className='row align-items-center'>
                                <div className="col-sm-8 offset-sm-2 emails-input-container">
                                    { emails.map((email, index) => (
                                        <div className='email-item' key={index}>
                                            <span className='text'>{email}</span>
                                            <span className='close' onClick={() => removeEmails(index)}>&times;</span>
                                        </div>
                                    )) }
                                    <input type="text"
                                        onKeyDown={handleKeyDown}
                                        className="emails-input"
                                    />
                                </div>

                                <div className="col-sm-2">
                                    <button type="submit" onClick={handleSubmit} className="btn btn-primary send-btn">Send</button>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-6 offset-sm-2">
                                    <span className="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default App;

if (document.getElementById('root')) {
    const element = document.getElementById('root');
    const token = element.dataset.accessToken;
    ReactDOM.render(<App />, element);
}
