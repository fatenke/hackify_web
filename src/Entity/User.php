<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Vote;

#[ORM\Entity]
class User
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_user;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom_user;

    #[ORM\Column(type: "string", length: 255)]
    private string $email_user;

    #[ORM\Column(type: "string", length: 255)]
    private string $role_user;

    #[ORM\Column(type: "integer")]
    private int $tel_user;

    #[ORM\Column(type: "string", length: 255)]
    private string $mdp_user;

    #[ORM\Column(type: "string", length: 255)]
    private string $adresse_user;

    #[ORM\Column(type: "string", length: 255)]
    private string $photo_user;

    #[ORM\Column(type: "string", length: 20)]
    private string $status_user;

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($value)
    {
        $this->id_user = $value;
    }

    public function getNom_user()
    {
        return $this->nom_user;
    }

    public function setNom_user($value)
    {
        $this->nom_user = $value;
    }

    public function getEmail_user()
    {
        return $this->email_user;
    }

    public function setEmail_user($value)
    {
        $this->email_user = $value;
    }

    public function getRole_user()
    {
        return $this->role_user;
    }

    public function setRole_user($value)
    {
        $this->role_user = $value;
    }

    public function getTel_user()
    {
        return $this->tel_user;
    }

    public function setTel_user($value)
    {
        $this->tel_user = $value;
    }

    public function getMdp_user()
    {
        return $this->mdp_user;
    }

    public function setMdp_user($value)
    {
        $this->mdp_user = $value;
    }

    public function getAdresse_user()
    {
        return $this->adresse_user;
    }

    public function setAdresse_user($value)
    {
        $this->adresse_user = $value;
    }

    public function getPhoto_user()
    {
        return $this->photo_user;
    }

    public function setPhoto_user($value)
    {
        $this->photo_user = $value;
    }

    public function getStatus_user()
    {
        return $this->status_user;
    }

    public function setStatus_user($value)
    {
        $this->status_user = $value;
    }

    #[ORM\OneToMany(mappedBy: "id_organisateur", targetEntity: Hackathon::class)]
    private Collection $hackathons;

        public function getHackathons(): Collection
        {
            return $this->hackathons;
        }
    
        public function addHackathon(Hackathon $hackathon): self
        {
            if (!$this->hackathons->contains($hackathon)) {
                $this->hackathons[] = $hackathon;
                $hackathon->setId_organisateur($this);
            }
    
            return $this;
        }
    
        public function removeHackathon(Hackathon $hackathon): self
        {
            if ($this->hackathons->removeElement($hackathon)) {
                // set the owning side to null (unless already changed)
                if ($hackathon->getId_organisateur() === $this) {
                    $hackathon->setId_organisateur(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "posted_by", targetEntity: Message::class)]
    private Collection $messages;

        public function getMessages(): Collection
        {
            return $this->messages;
        }
    
        public function addMessage(Message $message): self
        {
            if (!$this->messages->contains($message)) {
                $this->messages[] = $message;
                $message->setPosted_by($this);
            }
    
            return $this;
        }
    
        public function removeMessage(Message $message): self
        {
            if ($this->messages->removeElement($message)) {
                // set the owning side to null (unless already changed)
                if ($message->getPosted_by() === $this) {
                    $message->setPosted_by(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "id_participant", targetEntity: Participation::class)]
    private Collection $participations;

        public function getParticipations(): Collection
        {
            return $this->participations;
        }
    
        public function addParticipation(Participation $participation): self
        {
            if (!$this->participations->contains($participation)) {
                $this->participations[] = $participation;
                $participation->setId_participant($this);
            }
    
            return $this;
        }
    
        public function removeParticipation(Participation $participation): self
        {
            if ($this->participations->removeElement($participation)) {
                // set the owning side to null (unless already changed)
                if ($participation->getId_participant() === $this) {
                    $participation->setId_participant(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "idJury", targetEntity: Evaluation::class)]
    private Collection $evaluations;

        public function getEvaluations(): Collection
        {
            return $this->evaluations;
        }
    
        public function addEvaluation(Evaluation $evaluation): self
        {
            if (!$this->evaluations->contains($evaluation)) {
                $this->evaluations[] = $evaluation;
                $evaluation->setIdJury($this);
            }
    
            return $this;
        }
    
        public function removeEvaluation(Evaluation $evaluation): self
        {
            if ($this->evaluations->removeElement($evaluation)) {
                // set the owning side to null (unless already changed)
                if ($evaluation->getIdJury() === $this) {
                    $evaluation->setIdJury(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "user_id", targetEntity: PollVote::class)]
    private Collection $poll_votess;

        public function getPoll_votess(): Collection
        {
            return $this->poll_votess;
        }
    
        public function addPoll_votes(PollVote $poll_votes): self
        {
            if (!$this->poll_votess->contains($poll_votes)) {
                $this->poll_votess[] = $poll_votes;
                $poll_votes->setUser_id($this);
            }
    
            return $this;
        }
    
        public function removePoll_votes(PollVote $poll_votes): self
        {
            if ($this->poll_votess->removeElement($poll_votes)) {
                // set the owning side to null (unless already changed)
                if ($poll_votes->getUser_id() === $this) {
                    $poll_votes->setUser_id(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "idVotant", targetEntity: Vote::class)]
    private Collection $votes;

        public function getVotes(): Collection
        {
            return $this->votes;
        }
    
        public function addVote(Vote $vote): self
        {
            if (!$this->votes->contains($vote)) {
                $this->votes[] = $vote;
                $vote->setIdVotant($this);
            }
    
            return $this;
        }
    
        public function removeVote(Vote $vote): self
        {
            if ($this->votes->removeElement($vote)) {
                // set the owning side to null (unless already changed)
                if ($vote->getIdVotant() === $this) {
                    $vote->setIdVotant(null);
                }
            }
    
            return $this;
        }
}
