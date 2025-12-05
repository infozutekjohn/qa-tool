import React from "react";

const GeneralDetails = ({
    username,
    handleUsername,
    token,
    handleToken,
    endpoint,
    handleEndpoint,
}) => {
    return (
        <div className="container">
            <div className="mb-3">
                <label className="form-label">Username</label>
                <input
                    type="text"
                    value={username}
                    onChange={(e) => handleUsername(e.target.value)}
                    className="form-control"
                    required
                />
            </div>

            <div className="mb-3">
                <label className="form-label">Token</label>
                <input
                    type="text"
                    value={token}
                    onChange={(e) => handleToken(e.target.value)}
                    className="form-control"
                    required
                />
            </div>

            <div className="mb-3">
                <label className="form-label">Endpoint</label>
                <select
                    className="form-select"
                    aria-label="Default select example"
                    value={endpoint}
                    onChange={handleEndpoint}
                    defaultValue={"https://api-uat.agmidway.net/"}
                >
                    <option value="https://api-uat.agmidway.net/">
                        https://api-uat.agmidway.net
                    </option>
                    <option value="https://api.agmidway.com/">
                        https://api.agmidway.com
                    </option>
                </select>
            </div>
        </div>
    );
};

export default GeneralDetails;
