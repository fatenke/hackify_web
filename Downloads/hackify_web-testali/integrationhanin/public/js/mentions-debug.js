/**
 * Mentions Debug Script
 * This script adds a standalone debug button and functionality for testing @mentions
 */

// Add this when the page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Mentions Debug Script loaded');
    
    // Create and add the global debug button
    createDebugButton();
    
    // Also find test-mentions-btn and attach listener if it exists
    const testBtn = document.getElementById('test-mentions-btn');
    if (testBtn) {
        console.log('Test button found, attaching listener');
        testBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Test mentions button clicked');
            showDebugMentions();
        });
    }
    
    // Fix form validation for CKEditor
    fixFormSubmissionWithCKEditor();
});

// Function to fix form submission with CKEditor
function fixFormSubmissionWithCKEditor() {
    // Find the message form
    const messageForm = document.getElementById('message-form');
    if (!messageForm) {
        console.log('No message form found');
        return;
    }
    
    // Override the submit event
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submission intercepted');
        
        // Get the content from CKEditor if available
        let editorContent = '';
        
        // Try CKEditor 4 first
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['message-input']) {
            editorContent = CKEDITOR.instances['message-input'].getData();
            console.log('Content retrieved from CKEditor 4');
        } 
        // Then try CKEditor 5
        else if (typeof ClassicEditor !== 'undefined' && window.editor) {
            editorContent = window.editor.getData();
            console.log('Content retrieved from CKEditor 5');
        } 
        // Fall back to original textarea
        else {
            const textarea = document.getElementById('message-input');
            if (textarea) {
                editorContent = textarea.value;
                console.log('Content retrieved from textarea directly');
            }
        }
        
        // Check if we have content (validation)
        if (!editorContent.trim()) {
            alert('Please enter some content before submitting');
            return;
        }
        
        // Create a hidden input to hold the CKEditor content
        let hiddenInput = document.getElementById('hidden-message-content');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'hidden-message-content';
            hiddenInput.name = 'contenu';
            messageForm.appendChild(hiddenInput);
        }
        
        // Set the value and allow form to submit
        hiddenInput.value = editorContent;
        
        // Remove required attribute from the original textarea to prevent validation errors
        const originalTextarea = document.getElementById('message-input');
        if (originalTextarea && originalTextarea.hasAttribute('required')) {
            originalTextarea.removeAttribute('required');
        }
        
        // Log the submission data
        console.log('Submitting form with content:', editorContent);
        
        // Submit the form programmatically
        const formData = new FormData(messageForm);
        const chatId = getCurrentChatId();
        
        if (!chatId) {
            alert('Please select a chat first');
            return;
        }
        
        // Check if there are mentions in the content and extract them
        const mentions = extractMentionsFromContent(editorContent);
        if (mentions.length > 0) {
            // Add mentions as JSON to form data
            formData.append('mentions', JSON.stringify(mentions));
            console.log('Extracted mentions:', mentions);
        }
        
        // Determine the correct endpoint based on chat type
        let endpoint = `/chat/${chatId}/message/new`;
        const activeChat = document.querySelector('.chat-link.active');
        if (activeChat && activeChat.dataset.chatType === 'BOT_SUPPORT') {
            endpoint = `/chat/${chatId}/message/gemini`;
            console.log('Using bot endpoint for message');
        }
        
        // Disable submit button during request
        const submitButton = messageForm.querySelector('button[type="submit"]');
        if (submitButton) {
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            
            // Send the form data to the server
            fetch(endpoint, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 403) {
                        throw new Error('You do not have permission to post in this chat.');
                    }
                    return response.text().then(text => {
                        try {
                            const data = JSON.parse(text);
                            if (data.error) {
                                throw new Error(data.error);
                            }
                        } catch (e) {
                            // Not JSON or no error property
                        }
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Message sent successfully:', data);
                
                // Show success message
                showNotification('Message sent successfully!', 'success');
                
                // Clear editor content
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['message-input']) {
                    CKEDITOR.instances['message-input'].setData('');
                } else if (typeof ClassicEditor !== 'undefined' && window.editor) {
                    window.editor.setData('');
                } else if (originalTextarea) {
                    originalTextarea.value = '';
                }
                
                // Reload messages
                if (typeof loadChatContent === 'function') {
                    loadChatContent(chatId);
                } else if (window.loadChatContent) {
                    window.loadChatContent(chatId);
                } else {
                    // Try to find the function in the window scope
                    try {
                        eval(`loadChatContent(${chatId})`);
                    } catch (e) {
                        console.warn('Could not reload messages automatically:', e);
                        // As a last resort, reload the page
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                showNotification('Error: ' + error.message, 'error');
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        } else {
            // Fallback to natural form submission if button not found
            messageForm.submit();
        }
    });
}

// Helper function to get current chat ID from URL or data attribute
function getCurrentChatId() {
    // Try to find chat ID from URL
    const chatIdMatch = window.location.pathname.match(/\/chat\/(\d+)/);
    if (chatIdMatch && chatIdMatch[1]) {
        return chatIdMatch[1];
    }
    
    // Try to find from active chat link
    const activeChat = document.querySelector('.chat-link.active');
    if (activeChat && activeChat.dataset.chatId) {
        return activeChat.dataset.chatId;
    }
    
    // Try window variable as a last resort
    if (window.currentChatId) {
        return window.currentChatId;
    }
    
    return null;
}

// Function to create and add debug button to page
function createDebugButton() {
    // Create button element
    const debugBtn = document.createElement('button');
    debugBtn.id = 'debug-mentions-global';
    debugBtn.innerHTML = '<strong>DEBUG @MENTIONS</strong>';
    
    // Style the button
    debugBtn.style.position = 'fixed';
    debugBtn.style.bottom = '20px';
    debugBtn.style.right = '20px';
    debugBtn.style.backgroundColor = '#ff5722';
    debugBtn.style.color = 'white';
    debugBtn.style.padding = '15px 20px';
    debugBtn.style.border = 'none';
    debugBtn.style.borderRadius = '30px';
    debugBtn.style.fontWeight = 'bold';
    debugBtn.style.boxShadow = '0 4px 10px rgba(0,0,0,0.3)';
    debugBtn.style.cursor = 'pointer';
    debugBtn.style.zIndex = '999999';
    
    // Add hover effect
    debugBtn.addEventListener('mouseover', function() {
        this.style.backgroundColor = '#ff7043';
        this.style.transform = 'scale(1.05)';
    });
    
    debugBtn.addEventListener('mouseout', function() {
        this.style.backgroundColor = '#ff5722';
        this.style.transform = 'scale(1)';
    });
    
    // Add click handler
    debugBtn.addEventListener('click', function() {
        console.log('Global debug button clicked');
        showDebugMentions();
    });
    
    // Add to document
    document.body.appendChild(debugBtn);
    console.log('Debug button added to page');
}

// Main function to show debug mentions
function showDebugMentions() {
    console.log('Showing debug mentions dropdown');
    
    // Create or get mention container
    let mentionContainer = document.getElementById('mention-container');
    if (!mentionContainer) {
        console.log('Creating new mention container');
        mentionContainer = document.createElement('div');
        mentionContainer.id = 'mention-container';
        document.body.appendChild(mentionContainer);
    }
    
    // Style container to be visible
    mentionContainer.style.display = 'block';
    mentionContainer.style.position = 'fixed';
    mentionContainer.style.top = '50%';
    mentionContainer.style.left = '50%';
    mentionContainer.style.transform = 'translate(-50%, -50%)';
    mentionContainer.style.width = '350px';
    mentionContainer.style.minHeight = '200px';
    mentionContainer.style.maxHeight = '450px';
    mentionContainer.style.backgroundColor = 'white';
    mentionContainer.style.border = '3px solid #ff5722';
    mentionContainer.style.borderRadius = '8px';
    mentionContainer.style.boxShadow = '0 0 20px rgba(0,0,0,0.5)';
    mentionContainer.style.zIndex = '999999';
    mentionContainer.style.overflowY = 'auto';
    
    // Show loading state
    mentionContainer.innerHTML = `
        <div style="padding: 15px; background-color: #f0f5ff; border-bottom: 2px solid #0d6efd; font-weight: bold; position: sticky; top: 0; z-index: 1;">
            <span style="font-size: 16px; color: #0d6efd;">Loading Community Members...</span>
            <button id="close-mentions" style="position: absolute; top: 5px; right: 10px; background: none; border: none; font-size: 20px; cursor: pointer;">×</button>
        </div>
        <div style="padding: 30px; text-align: center;">
            <div style="width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid #0d6efd; border-radius: 50%; margin: 0 auto; animation: spin 1s linear infinite;"></div>
            <p style="margin-top: 20px; color: #666;">Fetching community members...</p>
        </div>
    `;
    
    // Add style for spinner animation
    if (!document.getElementById('mentions-debug-style')) {
        const style = document.createElement('style');
        style.id = 'mentions-debug-style';
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Add close button functionality
    setTimeout(() => {
        const closeBtn = document.getElementById('close-mentions');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                mentionContainer.style.display = 'none';
            });
        }
    }, 100);
    
    // Try to get community ID from the page
    let communauteId = null;
    try {
        // Look for communaute ID in various places
        const urlMatch = location.pathname.match(/\/communaute\/(\d+)/);
        if (urlMatch && urlMatch[1]) {
            communauteId = parseInt(urlMatch[1]);
        }
    } catch (e) {
        console.error('Error extracting community ID:', e);
    }
    
    console.log('Community ID:', communauteId);
    
    // Try to fetch members if we have an ID
    if (communauteId) {
        // Try API endpoints
        const apiUrl = `/chat/community/${communauteId}/members`;
        const fallbackUrl = `/community/${communauteId}/members`;
        
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('First API endpoint failed');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.members && data.members.length > 0) {
                    console.log('Members loaded from primary API:', data.members.length);
                    displayMentionMembers(data.members);
                } else {
                    throw new Error('Invalid data format from primary API');
                }
            })
            .catch(error => {
                console.error('Error with primary API:', error);
                
                // Try fallback URL
                fetch(fallbackUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Fallback API endpoint failed');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.members && data.members.length > 0) {
                            console.log('Members loaded from fallback API:', data.members.length);
                            displayMentionMembers(data.members);
                        } else {
                            throw new Error('Invalid data format from fallback API');
                        }
                    })
                    .catch(fallbackError => {
                        console.error('Error with fallback API:', fallbackError);
                        // Use test data
                        useTestMembers();
                    });
            });
    } else {
        // No community ID, use test data
        console.log('No community ID found, using test data');
        useTestMembers();
    }
    
    // Function to use test members data
    function useTestMembers() {
        const testMembers = [
            { id: 1, name: 'John Doe', role: 'ADMIN' },
            { id: 2, name: 'Jane Smith', role: 'ORGANISATEUR' },
            { id: 3, name: 'Alice Johnson', role: 'COACH' },
            { id: 4, name: 'Bob Brown', role: 'PARTICIPANT' },
            { id: 5, name: 'Charlie Davis', role: 'PARTICIPANT' }
        ];
        
        // Display after a short delay to simulate API call
        setTimeout(() => {
            console.log('Displaying test members');
            displayMentionMembers(testMembers);
        }, 1000);
    }
    
    // Function to display members in the dropdown
    function displayMentionMembers(members) {
        let html = `
            <div style="padding: 15px; background-color: #f0f5ff; border-bottom: 2px solid #0d6efd; font-weight: bold; position: sticky; top: 0; z-index: 1;">
                <span style="font-size: 16px; color: #0d6efd;">Community Members</span>
                <button id="close-mentions" style="position: absolute; top: 5px; right: 10px; background: none; border: none; font-size: 20px; cursor: pointer;">×</button>
            </div>
            <div style="padding: 10px; background-color: #f8f9fa; margin: 10px; border-radius: 5px;">
                <strong>Debug Info:</strong><br>
                - Found ${members.length} members<br>
                - CKEditor status: ${typeof ClassicEditor !== 'undefined' ? 'CKEditor 5 Available' : (typeof CKEDITOR !== 'undefined' ? 'CKEditor 4 Available' : 'No CKEditor')}
            </div>
        `;
        
        // Add members
        members.forEach(member => {
            const name = member.name || `${member.prenom || ''} ${member.nom || ''}`.trim();
            const role = member.role || 'USER';
            const initials = name.split(' ').map(n => n.charAt(0)).join('').toUpperCase();
            
            // Determine background color based on role
            let bgColor = '#6c757d'; // default
            if (role === 'ADMIN') bgColor = '#e63946';
            else if (role === 'ORGANISATEUR') bgColor = '#2a9d8f';
            else if (role === 'COACH') bgColor = '#e76f51';
            else if (role === 'PARTICIPANT') bgColor = '#457b9d';
            
            html += `
                <div style="display: flex; align-items: center; padding: 10px 15px; border-bottom: 1px solid #eee; cursor: pointer; transition: background-color 0.2s;" 
                    class="mention-member" data-id="${member.id}" data-name="${name}">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background-color: ${bgColor}; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 15px;">
                        ${initials}
                    </div>
                    <div>
                        <div style="font-weight: bold; color: #333;">${name}</div>
                        <div style="font-size: 12px; background-color: #f0f0f0; padding: 2px 8px; border-radius: 10px; display: inline-block;">${role}</div>
                    </div>
                </div>
            `;
        });
        
        mentionContainer.innerHTML = html;
        
        // Add hover effect to members
        mentionContainer.querySelectorAll('.mention-member').forEach(item => {
            item.addEventListener('mouseover', function() {
                this.style.backgroundColor = '#f0f5ff';
            });
            
            item.addEventListener('mouseout', function() {
                this.style.backgroundColor = '';
            });
        });
        
        // Add click handlers for members and close button
        setTimeout(() => {
            document.querySelectorAll('.mention-member').forEach(item => {
                item.addEventListener('click', function() {
                    const name = this.dataset.name;
                    console.log(`Selected member: ${name}`);
                    
                    // Insert mention
                    insertMention(name);
                });
            });
            
            const closeBtn = document.getElementById('close-mentions');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    mentionContainer.style.display = 'none';
                });
            }
        }, 100);
    }
    
    // Function to insert mention into editor
    function insertMention(name) {
        const mentionText = `@${name} `;
        let inserted = false;
        
        // Try CKEditor 5 (ClassicEditor)
        if (typeof ClassicEditor !== 'undefined' && window.editor) {
            try {
                window.editor.model.change(writer => {
                    window.editor.model.insertContent(writer.createText(mentionText));
                });
                inserted = true;
                console.log('Inserted mention using CKEditor 5');
            } catch (e) {
                console.error('Error inserting with CKEditor 5:', e);
            }
        }
        
        // Try CKEditor 4
        if (!inserted && typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['message-input']) {
            try {
                CKEDITOR.instances['message-input'].insertText(mentionText);
                inserted = true;
                console.log('Inserted mention using CKEditor 4');
            } catch (e) {
                console.error('Error inserting with CKEditor 4:', e);
            }
        }
        
        // Fall back to direct textarea manipulation
        if (!inserted) {
            const messageInput = document.getElementById('message-input');
            if (messageInput) {
                try {
                    const curPos = messageInput.selectionStart || 0;
                    const textBefore = messageInput.value.substring(0, curPos);
                    const textAfter = messageInput.value.substring(curPos);
                    messageInput.value = textBefore + mentionText + textAfter;
                    messageInput.focus();
                    messageInput.selectionStart = curPos + mentionText.length;
                    messageInput.selectionEnd = curPos + mentionText.length;
                    inserted = true;
                    console.log('Inserted mention using direct textarea manipulation');
                } catch (e) {
                    console.error('Error inserting with textarea:', e);
                    alert('Could not insert mention: ' + e.message);
                }
            }
        }
        
        // Alert user of success and hide mention container
        if (inserted) {
            console.log('Successfully inserted mention for: ' + name);
            mentionContainer.style.display = 'none';
        } else {
            console.error('Failed to insert mention, no compatible editor found');
            alert('Could not insert mention: No compatible editor found');
        }
    }
}

// Function to extract mentions from content
function extractMentionsFromContent(content) {
    const mentions = [];
    const mentionRegex = /@([A-Za-z0-9\s]+)/g;
    let match;
    
    // Find all @mentions in the content
    while ((match = mentionRegex.exec(content)) !== null) {
        const name = match[1].trim();
        
        // Only add unique mentions
        if (name && !mentions.some(m => m.name === name)) {
            // Try to find the user ID from the community members
            let userId = null;
            
            // Look in global community members if available
            if (window.communityMembers && Array.isArray(window.communityMembers)) {
                const member = window.communityMembers.find(m => 
                    (m.name && m.name.toLowerCase() === name.toLowerCase()) ||
                    ((m.prenom && m.nom) && 
                     (`${m.prenom} ${m.nom}`).toLowerCase() === name.toLowerCase())
                );
                
                if (member && member.id) {
                    userId = member.id;
                }
            }
            
            mentions.push({
                name: name,
                id: userId || null
            });
        }
    }
    
    return mentions;
}

// Helper function to show notifications
function showNotification(message, type = 'info') {
    // Check if notification container exists
    let notifContainer = document.getElementById('notification-container');
    if (!notifContainer) {
        notifContainer = document.createElement('div');
        notifContainer.id = 'notification-container';
        notifContainer.style.position = 'fixed';
        notifContainer.style.bottom = '20px';
        notifContainer.style.left = '20px';
        notifContainer.style.zIndex = '9999';
        document.body.appendChild(notifContainer);
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.style.backgroundColor = type === 'error' ? '#f44336' : '#4CAF50';
    notification.style.color = 'white';
    notification.style.padding = '12px 24px';
    notification.style.borderRadius = '4px';
    notification.style.marginTop = '10px';
    notification.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
    notification.style.minWidth = '250px';
    notification.innerHTML = `<div>${message}</div>`;
    
    // Add close button
    const closeBtn = document.createElement('span');
    closeBtn.innerHTML = '&times;';
    closeBtn.style.marginLeft = '10px';
    closeBtn.style.float = 'right';
    closeBtn.style.fontWeight = 'bold';
    closeBtn.style.fontSize = '20px';
    closeBtn.style.cursor = 'pointer';
    closeBtn.addEventListener('click', function() {
        notification.remove();
    });
    notification.appendChild(closeBtn);
    
    // Add to container
    notifContainer.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
} 