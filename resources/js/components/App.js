import {React, useState} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';

function App(props) {
    const [emails, setEmails] = useState([]);
    const [errorMessage, setErrorMessage] = useState('');
    const [successMessage, setSuccessMessage] = useState('');

    function handleKeyDown(e) {
        if (e.key !== 'Enter') {
            return;
        }

        const value = e.target.value;
        if (! value.trim() || emails.includes(value.trim())) {
            e.target.value = '';
            return;
        }

        if (emails.length >= 5) {
            setErrorMessage("Only 5 or less invitations can be sent at once");
            e.target.value = '';
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (! emailRegex.test(value) || value.length > 64) {
            setErrorMessage("Please enter a valid email address");
            return;
        }

        setEmails([...emails, value]);
        setErrorMessage("");
        e.target.value = '';
    }

    function removeEmails(index) {
        setEmails(emails.filter((email, i) => i !== index));

        if (emails.length <= 5) {
            setErrorMessage("");
        }
    }

    function handleSubmit(e) {
        const bodyParameters = {
            "emails": emails
        };

        axios.post('/submit-referral', bodyParameters).then(
            response => {
                if (response.data.status == "ok") {
                    setEmails([]);
                    setSuccessMessage("Success sent invitations");
                } else {
                    setErrorMessage(response.data.message);
                }
            }
        )
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
                                <div className="col-sm-8 offset-sm-2">
                                    <span className="text-danger">{errorMessage}</span>
                                    <span className="text-success">{successMessage}</span>
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
    ReactDOM.render(<App/>, document.getElementById('root'));
}
