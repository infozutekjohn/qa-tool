import React, { useEffect } from "react";

const TestFlow = ({ testGroups, setTestGroups }) => {
    const lockedTrue = { login: true, logout: true };

    useEffect(() => {
        setTestGroups((prev) => ({
            ...prev,
            ...lockedTrue,
        }));
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    const toggle = (key) => {
        if (key in lockedTrue) return;
        setTestGroups((prev) => ({ ...prev, [key]: !prev[key] }));
    };

    const flows = [
        ["login", "Generalised Login (required)"],
        ["casino", "Generalised Casino Tests (casino flows)"],
        ["live", "Generalised Live Casino Tests (live flows)"],
        ["bonus", "Generalised Bonus Flows"],
        ["error", "Generalised Error Handling"],
        ["gameslink", "Generalised Gameslink Feature Tests"],
        ["logout", "Generalised Logout (required)"],
    ];

    return (
        <div className="card">
            <div className="card-header fw-bold">Test Collections</div>
            <div className="card-body p-0">
                <div className="list-group list-group-flush">
                    {flows.map(([key, label]) => {
                        const isLocked = key in lockedTrue;
                        return (
                            <label
                                key={key}
                                className="list-group-item d-flex gap-2 align-items-start"
                                style={{ cursor: isLocked ? "not-allowed" : "pointer" }}
                            >
                                <input
                                    className="form-check-input mt-1"
                                    type="checkbox"
                                    checked={!!testGroups[key]}
                                    disabled={isLocked}
                                    onChange={() => toggle(key)}
                                />
                                <span className={isLocked ? "text-muted" : ""}>
                                    {label}
                                </span>
                            </label>
                        );
                    })}
                </div>
            </div>
        </div>
    );
};

export default TestFlow;
