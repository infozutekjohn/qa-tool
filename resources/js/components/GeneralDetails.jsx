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
        <div className="card mb-3">
            <div className="card-header fw-bold">General Details</div>
            <div className="card-body">
                {/* Username */}
                <div className="row align-items-center mb-3">
                    <div className="col-4">
                        <label className="form-label mb-0">Username</label>
                    </div>
                    <div className="col-8">
                        <input
                            type="text"
                            value={username}
                            onChange={(e) => handleUsername(e.target.value)}
                            className="form-control"
                            required
                        />
                    </div>
                </div>

                {/* Token */}
                <div className="row align-items-center mb-3">
                    <div className="col-4">
                        <label className="form-label mb-0">Token</label>
                    </div>
                    <div className="col-8">
                        <input
                            type="text"
                            value={token}
                            onChange={(e) => handleToken(e.target.value)}
                            className="form-control"
                            required
                        />
                    </div>
                </div>

                {/* Endpoint */}
                <div className="row align-items-center">
                    <div className="col-4">
                        <label className="form-label mb-0">Wallet Endpoint</label>
                    </div>
                    <div className="col-8">
                        <select
                            className="form-select"
                            value={endpoint}
                            onChange={handleEndpoint}
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
            </div>
        </div>
    );
};

export default GeneralDetails;
